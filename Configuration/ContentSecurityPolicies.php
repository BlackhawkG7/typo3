<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Security\ContentSecurityPolicy\Directive;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\Mutation;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\MutationCollection;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\MutationMode;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\Scope;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\SourceKeyword;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\SourceScheme;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\UriValue;
use TYPO3\CMS\Core\Type\Map;

return Map::fromEntries([
    // Provide declarations for the backend
    Scope::backend(),
    // NOTICE: When using `MutationMode::Set` existing declarations will be overridden

    new MutationCollection(
    // Results in `default-src 'self'`
        new Mutation(
            MutationMode::Set,
            Directive::DefaultSrc,
            SourceKeyword::self,
            new UriValue('*.pixxio.media'),
            new UriValue('https://fonts.gstatic.com'),
            SourceKeyword::unsafeInline
        ),

        // Extends the ancestor directive ('default-src'),
        // thus reuses 'self' and adds additional sources
        // Results in `img-src 'self' data: https://*.typo3.org`
        new Mutation(
            MutationMode::Extend,
            Directive::ImgSrc,
            SourceScheme::data,
            new UriValue('https://*.typo3.org')
        ),

        new Mutation(
            MutationMode::Extend,
            Directive::FrameSrc,
            new UriValue('https://plugin.pixx.io')
        ),

        // Extends the ancestor directive ('default-src'),
        // thus reuses 'self' and adds additional sources
        // Results in `script-src 'self' 'nonce-[random]'`
        // ('nonce-proxy' is substituted when compiling the policy)
        new Mutation(
            MutationMode::Extend,
            Directive::ScriptSrc,
            SourceKeyword::unsafeInline,
            new UriValue('*.pixxio.media')
        ),

        // Sets (overrides) the directive,
        // thus ignores 'self' of the 'default-src' directive
        // Results in `style-src blob:`
        new Mutation(
            MutationMode::Extend,
            Directive::StyleSrc,
            new UriValue('https://fonts.googleapis.com')
        ),
    ),
]);