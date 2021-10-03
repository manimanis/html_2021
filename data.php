<?php
$json = json_decode(file_get_contents('php://input'), TRUE);

$op = "";
if (isset($_POST['op'])) {
  $op = $_POST['op'];
} else if (isset($_GET['op'])) {
  $op = $_GET['op'];
}

if ($op == "") {
  die(json_encode([
    'error' => true,
    'messages' => 'Access permission denied.'
  ]));
}

require_once 'dbop.php';


$data = ['error' => false, 'data' => []];

if ($op == 'users') {
  $data['data']['users'] = fetch_users($pdo);
} else if ($op == 'login') {
  $now = time();
  $login = [
    'user_id' => intval($json['user_id']),
    'date_login' => date('Y-m-d H:i:s'),
    'date_expire' => date('Y-m-d H:i:s', $now + 7200),
    'ip_addr' => $_SERVER['REMOTE_ADDR'],
    'granted' => 0
  ];
  if (insert_login($pdo, $login)) {
    $login_id = $pdo->lastInsertId();
    delete_obsolete_logins($pdo, $_SERVER['REMOTE_ADDR'], $login_id);
    $data['data']['login'] = fetch_login_byid($pdo, $login_id);
  } else {
    $data['error'] = true;
    $data['messages'] = "Cannot login!";
  }
} else if ($op == 'fetch_login') {
  $login_id = intval($_GET['login_id']);
  delete_obsolete_logins($pdo, $_SERVER['REMOTE_ADDR'], $login_id);
  $login = fetch_login_byid($pdo, $login_id);
  if ($login == false || $_SERVER['REMOTE_ADDR'] != $login['ip_addr']) {
    $data['error'] = true;
    $data['messages'] = 'You are not allowed!';
  }
  if ($data['error'] == false) {
    $data['data']['login'] = $login;
  }
} else if ($op == 'fetch_user') {
  $user_id = intval($_GET['user_id']);
  $data['data']['user'] = fetch_user_byid($pdo, $user_id);
} else if ($op == 'logout') {
  $login_id = intval($json['login_id']);
  delete_login($pdo, $login_id);
  $data['messages'] = "Logged out successfully";
} else if ($op == 'active_login') {
  $act_login = fetch_login_byipaddr($pdo, $_SERVER['REMOTE_ADDR'], date("Y-m-d H:i:s"));
  if ($act_login == false) {
    $data['error'] = true;
    $data['messages'] = 'No active login yet!';
  } else {
    $user = fetch_user_byid($pdo, $act_login['user_id']);
    $data['data']['login'] = $act_login;
    $data['data']['user'] = $user;
  }
} else if ($op == 'fetch_questions') {
  $act_login = fetch_login_byipaddr($pdo, $_SERVER['REMOTE_ADDR'], date("Y-m-d H:i:s"));
  if ($act_login == false) {
    $data['error'] = true;
    $data['messages'] = 'Your session timed-out!';
  } else {
    $data['data']['questions'] = fetch_student_questions($pdo, $act_login['user_id']);
  }
} else if ($op == 'save_answer') {
  $date = date("Y-m-d H:i:s");
  $act_login = fetch_login_byipaddr($pdo, $_SERVER['REMOTE_ADDR'], $date);
  if ($act_login == false) {
    $data['error'] = true;
    $data['messages'] = 'Your session timed-out!';
  } else {
    if ($json['user_id'] != $act_login['user_id']) {
      $data['error'] = true;
      $data['messages'] = 'You cannot submit others answers!';
    }
    if (!$data['error']) {
      if ($json['rep_id'] == 0) {
        $reponse = fetch_student_answer_byquestionid($pdo, $json['user_id'], $json['question_id']);
        if ($reponse == false) {
          $reponse = [
            'user_id' => $json['user_id'],
            'question_id' => $json['question_id'],
            'reponse' => $json['reponse'],
            'note' => 0,
            'date_rep' => $date,
            'est_corrige' => 0,
            'date_correction' => null,
            'ip_addr' => $_SERVER['REMOTE_ADDR']
          ];
          $rep_id = insert_student_answer($pdo, $reponse);
        } else {
          $rep_id = $reponse['id'];
          $reponse['reponse'] = $json['reponse'];
          $reponse['note'] = 0;
          $reponse['est_corrige'] = 0;
          $reponse['date_rep'] = $date;
          $reponse['id_addr'] = $_SERVER['REMOTE_ADDR'];
          update_student_answer($pdo, $reponse);
        }
      } else {
        $reponse = fetch_student_answer_byid($pdo, $json['rep_id']);
        $reponse['reponse'] = $json['reponse'];
        $reponse['note'] = 0;
        $reponse['est_corrige'] = 0;
        $reponse['date_rep'] = $date;
        $reponse['id_addr'] = $_SERVER['REMOTE_ADDR'];
        update_student_answer($pdo, $reponse);
        $rep_id = $reponse['id'];
      }
    }
    $data['messages'] = 'Successfuly saved!';
    $json['rep_id'] = $rep_id;
    $data['data']['reponse'] = $json;
  }
}

echo json_encode($data);
