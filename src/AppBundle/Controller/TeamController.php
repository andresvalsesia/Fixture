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

	protected function processType(Request $request, $data, string $successMessage, $edit)
	{
		$teams = $this->getEr("AppBundle:Team")->findAll();

		$form = $this->createForm(TeamType::class,$data)
			->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$data = $form->getData();

			$exist = $this->getEr("AppBundle:Team")->nameExist($data);

			if ($exist) {
				$this->addFlash(
					"error",
					"{$data->getName()} ya esta cargado en el sistema"
				);

				return $this->redirect($this->generateUrl("team_create"));
			}

			$this->getEm()->persist($data);
			$this->getEm()->flush($data);

			$this->addFlash(
				"success",
				sprintf(
					$successMessage,
					$data->getName()
				)
			);

			return $this->redirect($this->generateUrl("team_create"));
		}

		return $this->render(
			"team.html.twig",
			[
				'form'  => $form->createView(),
    			'teams' => $teams,
    			'edit' 	=> $edit
			]
		);
	}

	public function createAction(Request $request)
	{
		return $this->processType(
	 		$request,
	 		new \AppBundle\Entity\Team(),
	 		"Se registro el equipo %s",
			false
	 	);
	}

	public function editAction(Request $request, $id)
	{
		if (! $team = $this->getEr("AppBundle:Team")->findOneById($id)) {
			http_response_code(404);
			die();
		}

		return $this->processType(
			$request,
			$team,
			"Se edito el equipo %s",
			true
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