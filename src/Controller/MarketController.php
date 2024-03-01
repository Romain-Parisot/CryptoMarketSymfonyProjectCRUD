<?php

namespace App\Controller;

use App\Entity\Market;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class MarketController extends AbstractController
{
    #[Route('/market', name: 'app_market')]
    public function index(EntityManagerInterface $entityManager, Request $r, SluggerInterface $slugger): Response
    {

        $marketData = $entityManager->getRepository(Market::class)->findAll();

        return $this->render('market/index.html.twig', [
            'marketData' => $marketData,
        ]);
    }
    #[Route('/market/delete/{id}', name: 'app_market_delete')]
    public function delete(EntityManagerInterface $entityManager, Request $r, Market $market)
    {
        if ($this->isCsrfTokenValid('delete' . $market->getId(), $r->request->get('csrf'))) {
            $entityManager->remove($market);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_market');
    }
}
