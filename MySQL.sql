CREATE DATABASE IF NOT EXISTS battleship;  ---créer une nouvelle base de données(Cette table servira à stocker les positions de toutes les cases du jeu pour tous les joueurs.)
USE battleship;

CREATE TABLE positions (
    id INT PRIMARY KEY AUTO_INCREMENT, ---clé primaire -> identifiant unique de chaque ligne.(id +++)
    joueur ENUM('J1','J2'), ---ENUM est une liste de valeurs possibles prédéfinies. (ONLY j1 et j2)
    ligne INT,
    colonne INT,
    bateau INT, ---Quel type de bateau occupe la case.
    touche TINYINT DEFAULT 0 ---C’est un petit entier (0 ou 1)/Par défaut → la case n’a pas été touchée.

);