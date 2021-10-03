<?php
$json = json_decode(file_get_contents('php://input'), TRUE);

$op = "";
if (isset($_POST['op'])) {
  $op = $_POST['op'];
} else if (isset($_GET['op'])) {
  $op = $_GET['op'];
}

if ($op == "" || $_SERVER['REMOTE_ADDR'] != '127.0.0.1') {
  die(json_encode([
    'error' => true,
    'messages' => 'Access permission denied.'
  ]));
}

require_once 'dbop.php';


$data = ['error' => false, 'data' => []];

if ($op == 'dates_list') {
  $data['data']['dates'] = fetch_dates_reponses($pdo);
} else if ($op == 'rep_by_page') {
  $est_corrige = (!key_exists("est_corrige", $_GET)) ? false : ($_GET['est_corrige'] == 'true');
  $page = (!key_exists("page", $_GET)) ? 0 : intval($_GET['page']);
  $page_size = (!key_exists("page_size", $_GET)) ? 20 : intval($_GET['page_size']);
  $data['data']['rep_by_page'] = fetch_reponses_bypage($pdo, $est_corrige, $page, $page_size);
  $data['data']['total'] = count_reponses($pdo, $est_corrige);
} else if ($op == 'delete_answer') {
  if (!isset($json['rep_id'])) {
    $data['error'] = true;
    $data['message'] = '"rep_id" is missing.';
  } else {
    $rep_id = intval($json['rep_id']);
    $answer = fetch_reponse_byid($pdo, $rep_id);
    if ($answer == false) {
      $data['error'] = true;
      $data['message'] = "The answer $rep_id could not be found.";
    } else {
      if (delete_reponse($pdo, $rep_id)) {
        $data['message'] = "The answer is successfuly deleted";
      }
    }
  }
} else if ($op == 'questions') {
  $data['data']['questions'] = fetch_questions($pdo);
} else if ($op == 'save_question') {
  // {"id":1,"sujet_id":1,"num_question":0,"nom_sujet":"Introduction","question":"What's the web?","reponse_modele":"sgsdfgsdg"}
  $fields = ['id', 'sujet_id', 'num_question', 'nom_sujet', 'question', 'reponse_modele'];
  $ok = true;
  foreach ($fields as $field) {
    if (!key_exists($field, $json)) {
      $ok = false;
      $data['error'] = true;
      $data['message'] .=  "$field is missing.\n";
    }
  }
  if ($ok) {
    if (!save_question($pdo, $json)) {
      $data['error'] = true;
      $data['message'] .= "Cannot save question.\n";
      $ok = false;
    }
    if ($ok && !save_sujet($pdo, $json)) {
      $data['error'] = true;
      $data['message'] .= "Cannot save subject.\n";
      $ok = false;
    }
    if ($ok) {
      $data['data']['question'] = fetch_question_byid($pdo, $json['id']);
    }
  }
} else if ($op == 'login_list') {
  $data['data']['logins'] = fetch_logins($pdo);
} else if ($op == 'grant') {
  $granted = intval($_GET['granted']);
  update_logins_granted($pdo, $json, $granted);
  $data['data']['logins'] = fetch_logins_byids($pdo, $json);
} else if ($op == 'delete_logins') {
  $data['data']['logins'] = delete_logins($pdo, $json);
} else if ($op == 'reponses_by_date') {
  $start_date = $_GET['date'];
  $data['data']['reponses'] = fetch_reponses_bydate($pdo, $start_date);
} else if ($op == 'update_mark') {
  $reponse = [
    'id' => intval($json['id']),
    'reponse' => $json['reponse'],
    'est_corrige' => intval($json['est_corrige']),
    'date_correction' => intval($json['est_corrige']) ? date("Y-m-d H:i:s") : null,
    'note' => doubleval($json['note'])
  ];
  update_reponse($pdo, $reponse);
  $data['data']['reponse'] = fetch_reponse_byid($pdo, $reponse['id']);
}

echo json_encode($data);
