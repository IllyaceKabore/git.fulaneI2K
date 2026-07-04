# 📱 Application web et Mobile de Suivi Scolaire Parent-Enfant d'un établissement primaire

**Université Joseph Ki-Zerbo — UFR/SEA**
**Projet :** Développement Mobile
**Enseignant :** Dr Lionel Marcus G. KABORET
---

## 🎯 Présentation du projet

Ce projet a été réalisé dans le cadre du cours de **Développement Mobile** dispensé par **Dr Lionel Marcus G. KABORET** enseignant à l'Université Joseph Ki-Zerbo.
Il consiste à concevoir et développer une **application mobile Android** permettant aux **parents d'élèves** de suivre en temps réel la scolarité de leurs enfants inscrits dans un établissement primaire, du **CP1 au CM2**.

L'application communique avec un **backend Laravel** exposant une **API REST**, développé en réutilisant les compétences acquises lors du projet web réalisé précédemment.

---

## 👥 Membres du groupe

Nom complet: KABORE MAMOUNATA,
Matricule: N03827320232,
Rôle ou Contribution principale: Développement BaAPckend (Laravel),
Contact: mamounatakabore770@gmail.com

Nom complet: KABORE ILLYACE, 
Matricule: E04923520221, 
Rôle ou Contribution principale: Développement Mobile (Android), 
Contact: illyace2kabore@gmail.com

---

## 🎯 Objectifs du projet

- Permettre aux parents de suivre la scolarité de leurs enfants sans se déplacer à l'école.
- Centraliser les informations scolaires, financières et administratives dans une seule application.
- Offrir une interface simple, intuitive et responsive.
- Mettre en pratique les compétences en développement Android et en API REST avec Laravel.

---

## ✅ Fonctionnalités

### 🔐 Authentification Parent
- [x] Connexion sécurisée par email et mot de passe
- [x] Déconnexion
- [x] Modification du mot de passe

### 🏠 Tableau de bord de l'élève
- [x] Affichage des informations de base (nom, prénom, photo, classe)
- [x] Affichage de la moyenne générale
- [x] Affichage du rang dans la classe
- [x] Résumé des dernières notes obtenues

### 📚 Consultation des notes
- [x] Liste des matières
- [x] Affichage des notes par matière
- [x] Calcul et affichage des moyennes trimestrielles

### 💰 Suivi des paiements
- [x] Historique des versements effectués
- [x] Montant total payé
- [x] Montant restant à payer
- [x] Consultation ou téléchargement du reçu de paiement (PDF)

### 📅 Suivi des absences
- [x] Liste des absences de l'élève
- [x] Motifs d'absence (si renseignés)

### 🔔 Notifications et annonces
- [x] Réception des annonces de l'école
- [x] Notifications importantes (examens, réunions, échéances de paiement)

---

## 🏗️ Architecture technique

L'application suit une architecture **client-serveur** :

```
 ┌─────────────────────────┐        Requêtes HTTP         ┌──────────────────────────┐
 │   Application Mobile     │ ───────────────────────────▶│      API REST Laravel    │
 │   (Android - Kotlin/Java)│ ◀───────────────────────────│   (PHP / Laravel Sanctum)│
 └─────────────────────────┘        Réponses JSON         └──────────────────────────┘
                                                                        │
                                                                        ▼
                                                              ┌──────────────────┐
                                                              │  Base de données │
                                                              │      MySQL       │
                                                              └──────────────────┘
```

- **Backend (Laravel)** : expose une API REST sécurisée (authentification par token via Laravel Sanctum) qui gère les élèves, notes, paiements, absences et annonces.
- **Frontend Mobile (Android)** : consomme l'API via Retrofit/OkHttp, affiche les données dans une interface ergonomique (Material Design), et gère l'état de connexion du parent.

---

## 📂 Structure du dépôt

```
suivi-scolaire-parent-enfant/
│
├── backend/                     # API REST Laravel
│   ├── app/
│   │   ├── Models/               # Modèles (Parent, Eleve, Note, Paiement, Absence, Annonce...)
│   │   ├── Http/
│   │   │   ├── Controllers/Api/  # Contrôleurs API
│   │   │   └── Middleware/
│   │   └── ...
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   ├── routes/
│   │   └── api.php               # Définition des routes API
│   ├── .env.example
│   └── composer.json
└── README.md           # Ce fichier


├── mobile/                       # Application Android
│   ├── app/
│   │   ├── src/main/java/...     # Code source Kotlin/Java
│   │   ├── src/main/res/         # Layouts, drawables, strings...
│   │   └── AndroidManifest.xml
│   ├── build.gradle
│   └── settings.gradle
                      
```


---

## 🛠️ Technologies utilisées

### Backend
- **Laravel** (PHP 8.x)
- **Laravel Sanctum** (authentification API par token)
- **MySQL** (base de données)
- **Composer** (gestionnaire de dépendances PHP)

### Mobile
- **Android Studio**
- **Kotlin** (ou Java, selon le choix du groupe)
- **Retrofit / OkHttp** (consommation de l'API REST)
- **SharedPreferences / DataStore** (stockage local du token de session)

### Outils
- **Git / GitHub** (gestion de version)
- **Postman** (test de l'API)

---

## ⚙️ Installation du Backend (API Laravel)

1. **Cloner le dépôt**
```bash
git clone https://github.com/IllyaceKabore/git.fulaneI2K/projet-suivi-scolaire.git
cd projet-suivi-scolaire
```

2. **Installer les dépendances PHP**
```bash
composer install
```

3. **Configurer l'environnement**
```bash
cp .env.example .env
php artisan key:generate
```

Modifier le fichier `.env` avec vos informations de base de données :
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=suivi_scolaire
DB_USERNAME=root
DB_PASSWORD=
```

4. **Créer la base de données**
```sql
CREATE DATABASE suivi_scolaire;
```

5. **Exécuter les migrations et les seeders (données de test)**
```bash
php artisan migrate --seed
```

6. **Lancer le serveur de développement**
```bash
php artisan serve
```

Par défaut, l'API sera accessible à l'adresse :
```
http://127.0.0.1:8000/api
```

---

## 📱 Installation de l'application mobile (Android)

1. **Ouvrir le projet dans Android Studio**
   - Ouvrir Android Studio
   - `File > Open` puis sélectionner le dossier `mobile/`

2. **Configurer l'URL de l'API**

   Dans le fichier de configuration réseau RetrofitClient, renseigner l'URL de base de l'API :
```kotlin
object RetrofitClient {
    const val BASE_URL = "http://10.0.0.1:8000/api/"
}
```

3. **Synchroniser le projet Gradle**
   - Cliquer sur **"Sync Project with Gradle Files"**

4. **Lancer l'application**
   - Sélectionner un émulateur ou connecter un appareil Android physique (débogage USB activé)
   - Cliquer sur **Run ▶**

5. **Se connecter**
   - Utiliser un [comptes de test] ci-dessous pour se connecter en tant que parent.

---

## 🔑 Comptes de test

Après exécution des seeders (`php artisan migrate --seed`), le comptes suivants est disponible :

Rôle, Email, Mot de passe
Parent, adamaouedraogo@gmail.bf, password123

---

## 🔒 Sécurité

- Authentification par **token** (Laravel Sanctum), aucun mot de passe stocké en clair.
- Les mots de passe sont **hashés** (bcrypt) côté backend.
- Chaque parent n'a accès qu'aux informations de **ses propres enfants** (contrôle d'accès par relation parent-élève).
- Communication prévue en **HTTPS** en environnement de production.

---

## 🧩 Difficultés rencontrées

*(Section à compléter par le groupe — exemples de points à aborder :)*
- Gestion de l'authentification par token entre Laravel Sanctum et Android (Retrofit).
- Configuration de l'adresse réseau entre l'émulateur et le serveur local.

---

## 🚀 Améliorations futures

- Ajout de notifications push (Firebase Cloud Messaging).
- Support multi-langue (français / anglais).
- Ajout d'un mode hors-ligne avec synchronisation.
- Tableau de bord multi-enfants pour les parents ayant plusieurs enfants inscrits.
- Export des bulletins de notes en PDF.

---

## 🤖 Utilisation de l'intelligence artificielle

Conformément aux indications du cahier des charges, des outils d'intelligence artificielle ont été utilisés **uniquement à des fins d'apprentissage** (compréhension de concepts, aide à la structuration du code, génération de documentation). L'ensemble du code produit a été **relu, compris et est justifiable** par les membres du groupe lors de la soutenance.

---
