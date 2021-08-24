<?php

namespace App\Controller;


use App\Repository\PokemonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Pokemon;
use PokePHP\PokeApi;


class PokemonController extends AbstractController
{
    public function deletePokemon()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $connection = $entityManager->getConnection();
        $platform   = $connection->getDatabasePlatform();
        $connection->executeUpdate($platform->getTruncateTableSQL('Pokemon', true /* whether to cascade */));
    }

    public function countPokemonTable(){
        $em = $this->getDoctrine()->getManager();
        $countPokemonTable = $em->getRepository(Pokemon::class);
        // how many rows there are
        $countPokemon = $countPokemonTable->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
        return $countPokemon;
    }

    #[Route('/pokemon', name: 'pokemon')]
    public function index(
        PokemonRepository $pokemonRepository
    ): Response
    {
        //count pokemon from table Pokemon
        $countPokemon = $this->countPokemonTable();
        $api = new PokeApi;
        $pokemon = $api->pokemon('');
        $pokemon = json_decode($pokemon);
        $totalPokemonApi = $pokemon->count;
        //if the $totalPokemonApi not eguals to $countPokemon at table Pokemon(the number of pokemon to 24/08/2021)
        if($totalPokemonApi != $countPokemon){
            $this->deletePokemon();
            //New insert list of all pokemon that exist
            $pokemonList = $api->pokemon('?offset=0&limit=1300');
            $pokemonList = json_decode($pokemonList,true);
            $pokemonListArray = $pokemonList['results'];

            for($i = 0;$i < $totalPokemonApi; $i++){
                $namePokemon =  $pokemonListArray[$i]['name'];
                $entityManager = $this->getDoctrine()->getManager();
                $pokemon = new Pokemon($namePokemon);
                $entityManager->persist($pokemon);
                // actually executes the queries (INSERT query)
                $entityManager->flush();
            }

        }



        return $this->render(
            'pokemon/index.html.twig',
            [
                'pokemons' => $pokemonRepository->findAll(),
            ]
        );
    }
}
