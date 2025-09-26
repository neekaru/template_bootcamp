<?php

namespace App\Livewire;

use Livewire\Component;

class ThemeManager extends Component
{
    public $theme = 'light';

    public function mount()
    {
        // Initialize theme from session or default
        $this->theme = session('theme', 'light');
    }

    public function toggleTheme()
    {
        $this->theme = $this->theme === 'light' ? 'dark' : 'light';
        session(['theme' => $this->theme]);
        
        // Dispatch browser event to update Alpine.js
        $this->dispatch('theme-updated', theme: $this->theme);
    }

    public function render()
    {
        return view('livewire.theme-manager');
    }
}
