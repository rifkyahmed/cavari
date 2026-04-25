<?php

namespace App\Csp\Policies;

use Spatie\Csp\Directive;
use Spatie\Csp\Policies\Policy;

class AppPolicy extends Policy
{
    public function configure()
    {
        $this
            // Keep default-src locked to self for everything else
            ->addDirective(Directive::DEFAULT, 'self')
            // Allow Cloudinary media (videos, audio) sources
            ->addDirective(Directive::MEDIA, ['self', 'https://res.cloudinary.com'])
            // Also permit images and fonts from Cloudinary if used elsewhere
            ->addDirective(Directive::IMG, ['self', 'https://res.cloudinary.com'])
            ->addDirective(Directive::FONT, ['self', 'https://res.cloudinary.com']);
    }
}
