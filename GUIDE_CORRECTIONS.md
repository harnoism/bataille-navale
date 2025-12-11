# 🎮 Guide des Corrections - Bataille Navale

Yo ! J'ai lu ton code et j'ai identifié les principaux soucis. Voici ce qu'il faut corriger **sans que je fasse le boulot pour toi** 😉

---

## 1️⃣ **Problème : Erreur de syntaxe dans `tir.php`**

### ❌ Le souci
```php
file_put_contents($fichier, json_encode(["hit" => true/false, "j2" => null]));
```

**Why it's broken:** `true/false` n'est pas du PHP valide. Ce n'est pas du code, c'est un commentaire en français ! 😅

### ✅ Ce qu'il faut faire
Tu dois choisir : est-ce que le coup est un `true` (touché) ou un `false` (manqué) ? 
- Remplace `true/false` par une **vraie valeur booléenne**
- Par exemple, au démarrage, tu pourrais initialiser à `false` (pas encore de coup joué)

**Conseil:** Réfléchis à la structure. Pourquoi tu as `"j2"` en `null` ? C'est quoi censé représenter ?

---

## 2️⃣ **Problème : Connexion à la base de données manquante**

### ❌ Le souci
Dans **`Plato.php`**, tu déclares une fonction `init_bdd()` qui attend une connexion PDO :
```php
function init_bdd(PDO $pdo, array $gridJ1, array $gridJ2)
```

**MAIS** tu ne **crées JAMAIS** cette connexion PDO ! Tu n'appelles jamais `init_bdd()` non plus.

### ✅ Ce qu'il faut faire

1. **Crée un fichier `config.php`** (ou utilise une partie de Plato.php) pour :
   - Définir tes identifiants de base de données (host, user, password, dbname)
   - Créer la connexion PDO avec un try/catch pour gérer les erreurs
   - Exemple structure :
   ```
   try {
       $pdo = new PDO("mysql:host=...", "user", "password");
   } catch (PDOException $e) {
       echo "Erreur de connexion : " . $e->getMessage();
   }
   ```

2. **Appelle la fonction `init_bdd()`** au bon moment :
   - Quand les deux joueurs se connectent (`index.php`)
   - Passe-lui les grilles des deux joueurs et la connexion PDO

3. **Utilise la même connexion PDO** dans `click_case.php` pour :
   - Récupérer si la case a un bateau
   - Mettre à jour la colonne `touche` à 1 si c'est un coup touché

---

## 3️⃣ **Problème : Logique de grille cassée dans `Plato.php`**

### ❌ Le souci
```php
<?php for ( $i = 0; $i < count($grid); $i++ ) :?>
    <div class="row"> 
        <?php for ($j = 0; $j < count($grid[$i]); $j++ ): ?> 
            <div class="col border">
                <form method="post" action="./click_case.php">
                    <button name="case" value="<?= $i ?>-<?= $j ?>"></button>
                </form>
            </div>
        <?php endfor; ?>
    </div>
<?php endfor; ?>
```

**Problems:**
- La grille est vide (tous les 0)
- Le code ne cherche **jamais** dans la base de données pour savoir où sont les bateaux
- Le bouton n'a pas de texte à l'écran (vide)
- Pas de CSS pour colorer les cases

### ✅ Ce qu'il faut faire

1. **Charge la vraie grille depuis la BDD** :
   - Au lieu d'une grille vide, fais une requête SQL
   - Récupère toutes les cases du joueur actuel depuis la table `positions`
   - Stocke le résultat dans une structure PHP (exemple : tableau associatif ou 2D)

2. **Affiche la grille dynamiquement** :
   - Pour chaque case, affiche sa couleur en fonction de `touche` :
     - `touche = 0` et `bateau > 0` → couleur normale (grise ?)
     - `touche = 1` et `bateau > 0` → ROUGE (touché !)
     - `touche = 1` et `bateau = 0` → BLEU (manqué)

3. **Mets du texte sur les boutons** :
   - Au lieu de boutons vides, affiche les coordonnées : `<?= $i."-".$j ?>`
   - Ou les étiquettes A-J pour lignes et 0-9 pour colonnes

4. **Ajoute du CSS** pour les couleurs :
   - `.touched { background-color: red; }`
   - `.miss { background-color: blue; }`
   - `.normal { background-color: grey; }`

---

## 4️⃣ **Problème : `click_case.php` ne fait rien**

### ❌ Le souci
```php
<?php
if (isset($_POST["case"])) {
    echo $_POST["case"];
    header('Location: ./index.php');
    exit;
}
```

**C'est vide !** Tu reçois les coordonnées mais tu ne les utilises pas du tout.

### ✅ Ce qu'il faut faire

1. **Récupère les coordonnées** du POST
2. **Connecte-toi à la BDD**
3. **Requête SQL** pour chercher si un bateau est à cette position :
   ```sql
   SELECT bateau, touche FROM positions 
   WHERE joueur = ? AND ligne = ? AND colonne = ?
   ```
4. **Mets à jour le statut** de la case (`touche = 1`)
5. **Renvoie une réponse** (JSON avec le résultat : touché ou manqué)
6. **Sauvegarde qui a joué** (pour alterner J1/J2)

---

## 5️⃣ **Problème : JSON au lieu de PDO ?**

### ❌ Le souci
Tu utilises `etat_joueurs.json` pour tracker les joueurs et `hit.json` pour les tirs.

C'est cool pour du prototypage MAIS le projet demande **une vraie base de données MySQL** ! Tu as le schéma SQL, alors utilise-le.

### ✅ Ce qu'il faut faire

1. **Migre tout vers MySQL** :
   - Les états des joueurs → Table `joueurs`
   - Les tirs → Déjà dans `positions` avec la colonne `touche`
   - Les scores → Table `scores`

2. **Garde les JSON juste pour le test en local** si tu veux, mais au final utilise SQL

---

## 6️⃣ **Problème : Pas de gestion d'alternance J1/J2**

### ❌ Le souci
Le code ne sait pas à qui c'est le tour ! Tu dois :

- Tracker **qui a joué en dernier**
- Empêcher le **même joueur de jouer deux fois** d'affilée
- Afficher **à qui c'est le tour**

### ✅ Ce qu'il faut faire

1. Crée une colonne `tour_actuel` dans ta BDD (ou une table `etat_partie`)
2. Après chaque coup, **change le tour**
3. Dans `Plato.php`, affiche qui doit jouer
4. Dans `click_case.php`, **vérifie que c'est le bon joueur** avant d'accepter le coup

---

## 7️⃣ **Problème : Pas de détection de victoire**

### ❌ Le souci
Tu ne sais jamais quand la partie est finie ! Le projet demande :
> "Un message 'Vous avez gagné' sera affiché lorsque tous les bateaux ont été coulés"

### ✅ Ce qu'il faut faire

1. **Crée une fonction** qui compte les bateaux coulés :
   ```php
   // Compter les cases de bateau NON touchées
   // Si c'est 0, c'est gagné !
   ```

2. **Après chaque coup**, appelle cette fonction
3. **Si victoire → affiche le message** et redirige vers une page de fin

---

## 📝 Résumé des actions prioritaires

| # | Tâche | Difficulté | Impact |
|---|-------|-----------|--------|
| 1 | Fixer `tir.php` (true/false) | 🟢 Facile | Bloquant |
| 2 | Créer connexion PDO | 🟡 Moyen | Bloquant |
| 3 | Appeler `init_bdd()` | 🟡 Moyen | Bloquant |
| 4 | Remplir `click_case.php` | 🟡 Moyen | Bloquant |
| 5 | Afficher grille depuis BDD | 🟠 Compliqué | Important |
| 6 | Gestion alternance J1/J2 | 🟠 Compliqué | Important |
| 7 | Détection victoire | 🟠 Compliqué | Important |

---

## 💡 Conseils pour avancer

1. **Teste au fur et à mesure** avec `phpinfo()` et `var_dump()`
2. **Active les erreurs PHP** : `error_reporting(E_ALL);`
3. **Utilise un client MySQL** (phpMyAdmin, HeidiSQL) pour voir ta BDD en temps réel
4. **Fais des commits Git** à chaque étape fonctionnelle
5. **Relis le sujet du projet** - tu as besoin d'une grille 10x10 avec les bateaux de différentes tailles

Bon courage ! Tu as une base solide, il faut juste la connecter et la finir. 💪

