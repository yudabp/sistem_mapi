<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Production;
use App\Models\FinancialTransaction;
use App\Models\Debt;
use Livewire\Livewire;

class NewFeaturesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function data_penjualan_sp_number_dropdown_works()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create production data
        $production = Production::create([
            'sp_number' => 'SP001',
            'tbs_quantity' => 1000,
            'kg_quantity' => 500,
            'production_date' => now(),
        ]);

        // Test Sales component
        $component = Livewire::test(\App\Livewire\Sales::class
        );

        // Check if SP number is in dropdown
        $component->assertSee('SP001');
    }

    /** @test */
    public function keuangan_perusahaan_creates_cash_book_income()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Test Financial component
        $component = Livewire::test(
            \App\Livewire\Financial::class
        );

        // Fill form and submit
        $component->set('transaction_date', now()->format('Y-m-d'))
            ->set('amount', 1000000)
            ->set('source_destination', 'Test Source')
            ->set('category', 'Test Category')
            ->call('saveTransaction');

        // Check if financial transaction was created
        $this->assertDatabaseHas('financial_transactions', [
            'transaction_type' => 'expense',
            'amount' => 1000000,
            'category' => 'Test Category',
        ]);

        // Check if corresponding cash book income was created
        $this->assertDatabaseHas('financial_transactions', [
            'transaction_type' => 'income',
            'amount' => 1000000,
            'category' => 'transfer_from_financial',
        ]);
    }

    /** @test */
    public function buku_kas_debt_payment_updates_debt_status()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create unpaid debt
        $debt = Debt::create([
            'amount' => 500000,
            'creditor' => 'Test Creditor',
            'due_date' => now()->addDays(30),
            'description' => 'Test Debt',
            'status' => 'belum_lunas',
        ]);

        // Test CashBook component
        $component = Livewire::test(
            \App\Livewire\CashBook::class
        );

        // Fill form as debt payment
        $component->set('transaction_date', now()->format('Y-m-d'))
            ->set('transaction_type', 'expense')
            ->set('amount', 500000)
            ->set('selected_debt_id', $debt->id)
            ->call('saveTransaction');

        // Check if debt status was updated
        $this->assertDatabaseHas('debts', [
            'id' => $debt->id,
            'status' => 'lunas',
        ]);

        // Check if cash book transaction was created
        $this->assertDatabaseHas('financial_transactions', [
            'transaction_type' => 'expense',
            'amount' => 500000,
            'source_destination' => 'Pembayaran Hutang - Test Creditor',
        ]);
    }

    /** @test */
    public function photo_modal_event_dispatching_works()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create sales record with proof
        $sales = FinancialTransaction::create([
            'transaction_date' => now(),
            'transaction_number' => 'TEST001',
            'transaction_type' => 'income',
            'amount' => 1000000,
            'source_destination' => 'Test',
            'proof_document_path' => 'sales_proofs/test.jpg',
            'category' => 'Sales',
        ]);

        // Test Sales component
        $component = Livewire::test(
            \App\Livewire\Sales::class
        );

        // Check if photo modal event is dispatched
        $component->assertSee('View Proof');
    }
}