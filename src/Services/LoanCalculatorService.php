<?php

namespace App\Services;

class LoanCalculatorService
{
    public function calculateLoan(float $amount, float $annualRate, int $durationYears): array
    {
        // Validation des paramètres
        if ($amount <= 0 || $annualRate < 0 || $durationYears <= 0) {
            throw new \InvalidArgumentException('Paramètres invalides pour le calcul de prêt');
        }

        // Calcul de la mensualité
        $monthlyPayment = $this->calculateMonthlyPayment($amount, $annualRate, $durationYears);
        
        $totalMonths = $durationYears * 12;
        $totalCost = $monthlyPayment * $totalMonths;
        $totalInterest = $totalCost - $amount;

        // Envoi de la réponse
        return [
            'monthly_payment' => $monthlyPayment,
            'total_months' => $totalMonths,
            'total_cost' => $totalCost,
            'total_interest' => $totalInterest
        ];
    }

    private function calculateMonthlyPayment(float $amount, float $annualRate, int $durationYears): float
    {
        $monthlyRate = $annualRate / 12 / 100;
        $numberOfPayments = $durationYears * 12;

        if ($monthlyRate == 0) {
            return $amount / $numberOfPayments;
        }

        $monthlyPayment = $amount *
            ($monthlyRate * pow(1 + $monthlyRate, $numberOfPayments)) /
            (pow(1 + $monthlyRate, $numberOfPayments) - 1);

        return round($monthlyPayment, 2);
    }
} 