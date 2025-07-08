#!/bin/bash

# Chemin vers le client MySQL de XAMPP
MYSQL="/opt/lampp/bin/mysql"

UTILISATEUR="root"
MOT_DE_PASSE=""
BASE="tp_flight"

# 1. Désactiver les contraintes pour permettre la suppression
$MYSQL -u "$UTILISATEUR" -p"$MOT_DE_PASSE" "$BASE" -e "SET FOREIGN_KEY_CHECKS = 0;"

# 2. Récupérer toutes les tables de la base
TABLES=$($MYSQL -u "$UTILISATEUR" -p"$MOT_DE_PASSE" -Nse "SELECT table_name FROM information_schema.tables WHERE table_schema = '$BASE';")

# 3. Boucle pour les supprimer
for t in $TABLES
do
  echo "Suppression de la table : $t"
  $MYSQL -u "$UTILISATEUR" -p"$MOT_DE_PASSE" "$BASE" -e "DROP TABLE IF EXISTS \`$t\`;"
done

# 4. Réactiver les contraintes
$MYSQL -u "$UTILISATEUR" -p"$MOT_DE_PASSE" "$BASE" -e "SET FOREIGN_KEY_CHECKS = 1;"