# TechMada RH — Gestion des conges (CI4 + SQLite)

Projet de gestion interne des demandes de conges avec 3 roles : employe, responsable RH et administrateur.

## Demarrage rapide

```bash
composer install
cp env .env
php spark migrate
php spark db:seed FitspaceSeeder
php spark serve
```

La base SQLite est `writable/fitspace.db` (voir [app/Config/Database.php](app/Config/Database.php)).

## Comptes de test

- Admin : admin@techmada.mg / admin123
- RH : rh@techmada.mg / rh123
- Employe : employe@techmada.mg / emp123

## Routes principales

- `/login` : connexion
- `/employe` : espace employe
- `/rh` : espace responsable RH
- `/admin` : espace admin

## Notes techniques

- Le solde est deduit uniquement a l'approbation.
- Annulation apres approbation : decremente `jours_pris`.
- CSRF actif sur tous les formulaires POST.
