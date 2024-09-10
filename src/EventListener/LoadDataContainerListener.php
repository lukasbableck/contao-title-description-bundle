<?php
namespace Lukasbableck\ContaoTitleDescriptionBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\CoreBundle\Routing\ScopeMatcher;
use Symfony\Component\HttpFoundation\RequestStack;

#[AsHook('loadDataContainer')]
class LoadDataContainerListener {
	public function __construct(private RequestStack $requestStack, private ScopeMatcher $scopeMatcher) {
	}

	public function __invoke(string $table): void {
		if (!\array_key_exists('pageTitle', $GLOBALS['TL_DCA'][$table]['fields'])
			&& !\array_key_exists('description', $GLOBALS['TL_DCA'][$table]['fields'])) {
			return;
		}

		$request = $this->requestStack->getCurrentRequest();
		if (null == $request || !$this->scopeMatcher->isBackendRequest($request) || 'edit' !== $request->query->get('act')) {
			return;
		}

		$GLOBALS['TL_CSS'][] = 'bundles/contaotitledescription/backend.css';
		$GLOBALS['TL_JAVASCRIPT'][] = 'bundles/contaotitledescription/backend.js';
	}
}
