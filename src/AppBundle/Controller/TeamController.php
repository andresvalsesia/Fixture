<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\TeamType;

class TeamController extends Controller
{
	protected function getEm()
	{
		return $this->getDoctrine()->getManager();
	}

	protected function getEr(string $name)
	{
		return $this->getEm()->getRepository($name);
	}

	public function createAction(Request $request)
	{
	 	$teams = $this->getEr("AppBundle:Team")->findAll();

	 	$team = new \AppBundle\Entity\Team();

		$form = $this->createForm(TeamType::class,$team);

		$form->handleRequest($request);

		if( $form->isSubmitted() && $form->isValid() ){

			$name = trim( $form->get("name")->getData() );
			$exist = $this->getEr("AppBundle:Team")->nameExist($name);

			if ($exist) {

				$this->addFlash("error", "{$name} ya esta cargado en el sistema");

				return $this->redirect($this->generateUrl("team_create"));

			}

			$team = $form->getData();
			$this->getEm()->persist($team);
			$this->getEm()->flush($team);

			$this->addFlash("success", "{$name} se ha creado correctamente.");

			return $this->redirect($this->generateUrl("team_create"));

		}

		return $this->render("team.html.twig", [

    				"form" => $form->createView(),
    				"teams" => $teams

		]);
	}

	public function editAction(Request $request, $id)
	{
		if (! $team = $this->getEr("AppBundle:Team")->findOneById($id)) {
			http_response_code(404);
			die();
		}

		$form = $this->createForm(TeamType::class, $team)
			->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$team = $form->getData();

			$this->getEm()->persist($team);
			$this->getEm()->flush($team);

			$this->addFlash(
				"success",
				"Se ha editado correctamente el equipo {$team->getName()} con ID : {$id}"
			);

			return $this->redirect($this->generateUrl("team_create"));
		}

		return $this->render(
			"edit_team.html.twig",
			[
				"form" => $form->createView(),
			]
		);
	}

	public function deleteAction(Request $request, $id)
	{
		if (! $team = $this->getEr("AppBundle:Team")->findOneById($id)) {
			http_response_code(404);
			die();
		}

		try {
			$this->getEm()->remove($team);
			$this->getEm()->flush();

			$this->addFlash(
				"success",
				"Se ha eliminado correctamente el equipo {$team->getName()} con ID : {$id}"
			);
		}
		catch (\Exception $e) {
			$this->addFlash(
				"error",
				"No se pudo eliminar el equipo {$team->getName()} con ID : {$id}"
			);
		}

		return $this->redirect($this->generateUrl("team_create"));
	}
}