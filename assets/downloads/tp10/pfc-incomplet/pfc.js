// Numéro du gagnant d'une partie
let g = -1; // -1 : partie non terminée - 0 : partie nulle
            //  1 : vous gagnez         - 2 : vous perdez

// Les 3 boutons utilisateur
const userBtns = document.querySelectorAll("à compléter"); // TODO : à compléter
// Le bouton réservé à l'ordinateur
const compBtn = document.querySelector("à compléter");     // TODO : à compléter
// Le div réservé au résultat
const divRes = document.querySelector("à compléter");    // TODO : à compléter
// Le bouton rejouer
const btnRejouer = document.querySelector("à compléter");     // TODO : à compléter
// Les éléments du jeu
const classes = ['pierre', 'feuille', 'ciseaux'];
const gagnants = ["Partie nulle", "Vous avez gagné!", "Vous avez perdu!"];


// Gestionnaire d'évènement des boutons utilisateur
userBtns.forEach((btn, nb) => {
  btn.onclick = (e) => {
    // S'il y'a un gagnant
    // Le clic sur le bouton ne fait rien 
    if (g != -1) {
      return;
    }

    // Récupère le numéro du bouton
    const j1 = nb;
    // Calcule un numéro aléatoire pour le PC
    const j2 = Math.floor(Math.random() * 3);
    // Affiche le choix du PC
    compBtn.className = classes[j2];
    // Cache les autres boutons qui n'ont pas été cliqués
    userBtns.forEach(btn1 => {
      if (btn1 != btn) {
        btn1.style.display = "none";
      }
    });

    // Calcule le numéro du gagnant
    // 0 : Pas de gagnant
    // 1 : L'utilisateur est gagnant
    // 2 : L'utilisateur est perdant
    g = (j1 - j2 + 3) % 3;

    // Affiche le gagnant
    divRes.textContent = gagnants[g];
    // Réaffiche le bouton rejouer
    btnRejouer.style.display = "";
  };
});


// Gestionnaire d'évènement bouton rejouer
btnRejouer.onclick = (e) => {
  // Aucun gagnant
  g = -1;
  // Cacher le bouton rejouer
  btnRejouer.style.display = "none";
  // Ré-initialise le choix du PC à ?
  compBtn.className = "none";
  // Vider le champ réservé poir l'affichage du gagnant
  divRes.textContent = "";
  // Réaffiche les boutons utilisateur
  userBtns.forEach(btn => {
    btn.style.display = "";
  });
};

// Cacher le bouton rejouer
btnRejouer.style.display = "none";