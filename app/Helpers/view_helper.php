<?php

if (! function_exists('statut_class')) {
    function statut_class(string $statut): string
    {
        $map = [
            'en_attente' => 's-attente',
            'approuvee' => 's-approuvee',
            'refusee' => 's-refusee',
            'annulee' => 's-annulee',
        ];

        return $map[$statut] ?? 's-attente';
    }
}

if (! function_exists('type_badge_class')) {
    function type_badge_class(string $libelle): string
    {
        $label = strtolower($libelle);

        if (str_contains($label, 'annuel')) {
            return 't-annuel';
        }
        if (str_contains($label, 'maladie')) {
            return 't-maladie';
        }
        if (str_contains($label, 'special')) {
            return 't-special';
        }
        if (str_contains($label, 'sans solde')) {
            return 't-sans-solde';
        }

        return '';
    }
}

if (! function_exists('format_date')) {
    function format_date(?string $date, string $format = 'd/m/Y'): string
    {
        if (! $date) {
            return '-';
        }

        $timestamp = strtotime($date);
        if ($timestamp === false) {
            return $date;
        }

        return date($format, $timestamp);
    }
}

if (! function_exists('initials')) {
    function initials(?string $prenom, ?string $nom): string
    {
        $first = $prenom ? strtoupper(substr($prenom, 0, 1)) : '';
        $last = $nom ? strtoupper(substr($nom, 0, 1)) : '';
        $value = $first . $last;

        return $value !== '' ? $value : '??';
    }
}
