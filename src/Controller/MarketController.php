<?php

namespace App\Controller;

use App\Entity\Market;
use App\Form\CryptoType;
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
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $favorites = $user->getMarkets();
        $marketData = $entityManager->getRepository(Market::class)->findAll();
        return $this->render('market/index.html.twig', [
            'marketData' => $marketData,
            'user' => $user,
            'favorites' => $favorites,
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
    #[Route('/market/edit/{slug}', name: 'app_market_edit')]
    public function edit(EntityManagerInterface $entityManager, Request $r, Market $market)
    {
        $form = $this->createForm(CryptoType::class, $market);
        $form->handleRequest($r);
        if ($form->isSubmitted() && $form->isValid()) {
            $market->setName($form->get('name')->getData());
            $market->setCode($form->get('code')->getData());
            $market->setPrice($form->get('price')->getData());
            $market->setMarketCap($form->get('market_cap')->getData());
            $market->setMaxSupply($form->get('max_supply')->getData());
            $entityManager->persist($market);
            $entityManager->flush();
            return $this->redirectToRoute('app_market');
        }
        return $this->render('market/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/market/add', name: 'app_market_add')]
    public function add(EntityManagerInterface $entityManager, Request $r, SluggerInterface $slugger)
    {
        $market = new Market();
        $form = $this->createForm(CryptoType::class, $market);
        $form->handleRequest($r);
        if ($form->isSubmitted() && $form->isValid()) {
            $market->setName($form->get('name')->getData());
            $market->setCode($form->get('code')->getData());
            $market->setPrice($form->get('price')->getData());
            $market->setMarketCap($form->get('market_cap')->getData());
            $market->setMaxSupply($form->get('max_supply')->getData());
            $market->setSlug($slugger->slug($market->getName()). '-' . uniqid());
            $entityManager->persist($market);
            $entityManager->flush();
            return $this->redirectToRoute('app_market');
        }
        return $this->render('market/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/market/favorite/{id}', name: 'app_market_favorite')]
    public function favorite(EntityManagerInterface $entityManager, Request $r, Market $market)
        {
        if ($this->isCsrfTokenValid('favorite' . $market->getId(), $r->request->get('csrf'))) {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        
        if ($user->getMarkets()->contains($market)) {
            $user->removeMarket($market);
        } else {
            $user->addMarket($market);
        }

        $entityManager->persist($user);
        $entityManager->flush();
        }
        return $this->redirectToRoute('app_market');
    }
}
