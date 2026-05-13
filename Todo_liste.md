# Todo Liste — Projet FitSpace (CI4 + SQLite3)

> **Total estimé :** 790 min | **Temps passé :** 0 min | **Reste :** 790 min

---

##  Base de données

| # | Description | Type | Estimation | Temps passé | Reste | Avancement | Personne |
|---|-------------|------|-----------|------------|-------|------------|----------|
| 1 | Créer projet CI4 + configurer SQLite3 (fitspace.db) | Setup | 10 | 0 | 10 | 0% | Ismael |
| 2 | Créer migration : departements | Migration | 5 | 0 | 5 | 0% | Ismael |
| 3 | Créer migration : types_conge | Migration | 5 | 0 | 5 | 0% | Ismael |
| 4 | Créer migration : employes (email UNIQUE, role, actif) | Migration | 5 | 0 | 5 | 0% | Ismael |
| 5 | Créer migration : soldes (employe_id FK, type_conge_id FK, annee) | Migration | 5 | 0 | 5 | 0% | Ismael |
| 6 | Créer migration : conges (statut, traite_par FK) | Migration | 5 | 0 | 5 | 0% | Ismael |
| 7 | Créer Seeder : 1 admin + 2 employés + 3 types_conge + soldes | Seeder | 10 | 0 | 10 | 0% | Ismael |
| 8 | Vérifier php spark migrate && php spark db:seed | Test | 5 | 0 | 5 | 0% | Ismael |
| 9 | EmployeModel.php (table: employes) | Modèle | 10 | 0 | 10 | 0% | Ismael |
| 10 | DepartementModel.php (table: departements) | Modèle | 5 | 0 | 5 | 0% | Mickael |
| 11 | TypeCongeModel.php (table: types_conge) | Modèle | 5 | 0 | 5 | 0% | Mickael |
| 12 | SoldeModel.php (table: soldes) | Modèle | 10 | 0 | 10 | 0% | Mickael |
| 13 | CongeModel.php (table: conges) | Modèle | 10 | 0 | 10 | 0% | Mickael |

---

##  Authentification

| # | Description | Type | Estimation | Temps passé | Reste | Avancement | Personne |
|---|-------------|------|-----------|------------|-------|------------|----------|
| 14 | Groupes de routes : /employe / /rh / /admin | Routing | 10 | 0 | 10 | 0% | Ismael |
| 15 | AuthFilter : rediriger si session absente | Filtre | 15 | 0 | 15 | 0% | Ismael |
| 16 | AdminFilter : vérifier rôle = admin | Filtre | 15 | 0 | 15 | 0% | Ismael |
| 17 | Activer CSRF sur tous les formulaires POST | Sécurité | 5 | 0 | 5 | 0% | Ismael |
| 18 | Layout partagé layout/app.php + sidebar selon rôle | Vue | 15 | 0 | 15 | 0% | Mickael |
| 19 | Controller : page login (formulaire email + password) | Fonction | 20 | 0 | 20 | 0% | Mickael |
| 20 | Controller : traitement login (password_verify + session) | Fonction | 15 | 0 | 15 | 0% | Mickael |
| 21 | Controller : logout (destruction session) | Fonction | 10 | 0 | 10 | 0% | Mickael |
| 22 | Flashdata CI4 pour messages succès / erreur | Vue | 10 | 0 | 10 | 0% | Mickael |

---

##  Profil

| # | Description | Type | Estimation | Temps passé | Reste | Avancement | Personne |
|---|-------------|------|-----------|------------|-------|------------|----------|
| 23 | Controller : afficher profil (nom, email, soldes par type) | Fonction | 25 | 0 | 25 | 0% | Ismael |
| 24 | Controller : modifier profil (nom + mot de passe) | Fonction | 20 | 0 | 20 | 0% | Ismael |
| 25 | Vue : page profil employé | Vue | 15 | 0 | 15 | 0% | Ismael |

---

##  Dashboard (Espace Employé)

| # | Description | Type | Estimation | Temps passé | Reste | Avancement | Personne |
|---|-------------|------|-----------|------------|-------|------------|----------|
| 26 | Controller : soumettre demande de congé (type, dates, motif) | Fonction | 30 | 0 | 30 | 0% | Ismael |
| 27 | Calcul nb_jours ouvrables (exclure week-ends / Carbon) | Logique | 20 | 0 | 20 | 0% | Ismael |
| 28 | Validation : date_debut < date_fin | Logique | 10 | 0 | 10 | 0% | Ismael |
| 29 | Validation : solde insuffisant → flash erreur | Logique | 15 | 0 | 15 | 0% | Ismael |
| 30 | Validation : pas de chevauchement avec demande active | Logique | 15 | 0 | 15 | 0% | Mickael |
| 31 | Controller : lister ses propres demandes + statuts | Fonction | 20 | 0 | 20 | 0% | Mickael |
| 32 | Controller : annuler une demande en attente | Fonction | 15 | 0 | 15 | 0% | Mickael |
| 33 | Controller : voir solde restant par type | Fonction | 15 | 0 | 15 | 0% | Mickael |
| 34 | Vues : formulaire soumission / liste demandes / solde | Vue | 20 | 0 | 20 | 0% | Mickael |

---

## 🧑‍💼 Espace RH

| # | Description | Type | Estimation | Temps passé | Reste | Avancement | Personne |
|---|-------------|------|-----------|------------|-------|------------|----------|
| 35 | Controller : voir toutes les demandes en attente | Fonction | 20 | 0 | 20 | 0% | Mickael |
| 36 | Controller : approuver une demande (commentaire optionnel) | Fonction | 25 | 0 | 25 | 0% | Mickael |
| 37 | Logique : UPDATE soldes jours_pris += nb_jours à l'approbation | Logique | 20 | 0 | 20 | 0% | Mickael |
| 38 | Logique : vérifier jours_pris + nb_jours <= jours_attribues | Logique | 15 | 0 | 15 | 0% | Mickael |
| 39 | Controller : refuser une demande (solde intact) | Fonction | 15 | 0 | 15 | 0% | Mickael |
| 40 | Logique : annulation après approbation → décrémenter jours_pris | Logique | 15 | 0 | 15 | 0% | Ismael |
| 41 | Controller : filtrer demandes par département ou statut | Fonction | 20 | 0 | 20 | 0% | Ismael |
| 42 | Controller : voir le solde de chaque employé | Fonction | 15 | 0 | 15 | 0% | Ismael |
| 43 | Vues : liste demandes RH / formulaire approbation | Vue | 20 | 0 | 20 | 0% | Ismael |

---

## 🛠️ Espace Admin

| # | Description | Type | Estimation | Temps passé | Reste | Avancement | Personne |
|---|-------------|------|-----------|------------|-------|------------|----------|
| 44 | Controller : CRUD employés (créer, éditer, désactiver) | Fonction | 30 | 0 | 30 | 0% | Ismael |
| 45 | Controller : CRUD départements | Fonction | 20 | 0 | 20 | 0% | Ismael |
| 46 | Controller : CRUD types de congé (libelle, jours_annuels, deductible) | Fonction | 20 | 0 | 20 | 0% | Ismael |
| 47 | Controller : tableau de bord absences du mois en cours | Fonction | 25 | 0 | 25 | 0% | Mickael |
| 48 | Controller : initialiser / ajuster solde annuel d'un employé | Fonction | 20 | 0 | 20 | 0% | Mickael |
| 49 | Controller : historique complet de toutes les demandes | Fonction | 15 | 0 | 15 | 0% | Mickael |
| 50 | Vues : CRUD employés / dashboard absences | Vue | 25 | 0 | 25 | 0% | Mickael |

---

## ✅ Finalisation

| # | Description | Type | Estimation | Temps passé | Reste | Avancement | Personne |
|---|-------------|------|-----------|------------|-------|------------|----------|
| 51 | Pattern PRG respecté partout (POST → redirect) | Routing | 10 | 0 | 10 | 0% | Ismael |
| 52 | Test global : migrate + seed + login 3 rôles + workflow congé | Test | 20 | 0 | 20 | 0% | Mickael |
| 53 | Rédiger README (instructions + comptes de test) | Doc | 15 | 0 | 15 | 0% | Ismael |

---

## 📈 Récapitulatif

| Catégorie | Nb tâches | Estimation (min) |
|-----------|-----------|-----------------|
| Base de données | 13 | 80 |
| Authentification | 9 | 115 |
| Profil | 3 | 60 |
| Dashboard | 9 | 160 |
| Espace RH | 9 | 165 |
| Espace Admin | 7 | 155 |
| Finalisation | 3 | 45 |
| **TOTAL** | **53** | **780** |
