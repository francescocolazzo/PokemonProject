<?php

namespace App\Controller;

use App\Entity\PokemonTeam;
use App\Entity\Team;
use App\Form\TeamType;
use App\Repository\PokemonTeamRepository;
use App\Repository\TeamPokemonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Pokemon;
use PokePHP\PokeApi;

#[Route('/')]
class TeamController extends AbstractController
{
    public function deletePokemon()
    {
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
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

    #[Route('/team/list', name: 'team_index', methods: ['GET'])]
    public function index(TeamPokemonRepository $teamPokemonRepository): Response
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
                $em = $this->getDoctrine()->getManager();
                $pokemon = new Pokemon($namePokemon);
                $em->persist($pokemon);
                $em->flush();
            }
        }
        return $this->render('team/index.html.twig', [
            'teams' => $teamPokemonRepository->findBy(array(),array('creationDate' => 'DESC')),
        ]);
    }

    #[Route('/team/create', name: 'team_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $team = new Team();
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);
        //save team button
        if ($form->isSubmitted() && $form->isValid()) {
            $team->setCreationDate(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($team);
            $em->flush();
            $teamId = $team->getId();
            return $this->redirectToRoute('team_edit', ['id' => $teamId], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('team/new.html.twig', [
            'team' => $team,
            'form' => $form
        ]);

    }

    #[Route('/{id}', name: 'team_show', methods: ['GET'])]
    public function show(Team $team): Response
    {
        return $this->render('team/show.html.twig', [
            'team' => $team,
        ]);
    }

    #[Route('/{id}/edit/{CatchPokemon?}', name: 'team_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Team $team): Response
    {
        $api = new PokeApi;
        $catch = $request->get('CatchPokemon');
        $idTeam = intval($request->get('id'));
        $catchPokemon = $catch;
        //Gotta catch em all button
        if($catchPokemon == 'ok'){
            $countPokemon = $this->countPokemonTable();
            $randomPokemondId = rand(1,$countPokemon);
            $em = $this->getDoctrine()->getManager();
            $findPokemonName = $em->getRepository(Pokemon::class)->find($randomPokemondId);
            $findPokemonName = $findPokemonName->getName();

            $pokemon = $api->pokemon($findPokemonName);
            $pokemon = json_decode($pokemon);
            $pokemonName = $pokemon->name;
            $pokemonBaseExp = $pokemon->base_experience;
            $pokemonSprite = $pokemon->sprites->front_default;
            $abilities = '';
            foreach ( $pokemon->abilities as $ability){
                if($abilities != ''){
                    $abilities .= ", " . $ability->ability->name;
                }else{
                    $abilities = $ability->ability->name;
                }
            }
            $types = '';
            foreach ( $pokemon->types as $type){
                if($types != ''){
                    $types .= ", " . $type->type->name;
                }else{
                    $types = $type->type->name;
                }
            }
            $em = $this->getDoctrine()->getManager();
            $pokemon = new PokemonTeam($pokemonName,$pokemonBaseExp,$pokemonSprite,$abilities,$types,$team);
            $em->persist($pokemon);
            $em->flush();
        }
        $idPokemon = intval($request->get('idPokemon'));
        if($idPokemon != 0){
            $em = $this->getDoctrine()->getManager();
            $pokemonFromTeam = $em->getRepository(PokemonTeam::class)->find($idPokemon);
            if($pokemonFromTeam != null){
                $em->remove($pokemonFromTeam);
                $em->flush();
            }
        }

        $pokemons = $team->getPokemons();
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);
        //Update Team  name button
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('team_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('team/edit.html.twig', [
            'team' => $team,
            'form' => $form,
            'id' => $idTeam,
            'pokemons' => $pokemons
        ]);
    }

    #[Route('/{id}', name: 'team_delete', methods: ['POST'])]
    public function delete(Request $request, Team $team): Response
    {
        if ($this->isCsrfTokenValid('delete'.$team->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $listPokemonForId = $team->getPokemons();
            foreach ($listPokemonForId as $p){
                $em->remove($p);
            }
            $em->remove($team);
            $em->flush();
        }
        return $this->redirectToRoute('team_index', [], Response::HTTP_SEE_OTHER);
    }
}
