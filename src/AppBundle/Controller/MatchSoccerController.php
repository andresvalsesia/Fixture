<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\MatchSoccerType;

class MatchSoccerController extends Controller
{
	private function getEm()
	{
		return $this->getDoctrine()->getManager();
	}

	private function getEr($name)
	{
		return $this->getEm()->getRepository($name);
	}

	public function listAction(Request $request)
	{
		return $this->render(
			"match.html.twig",
			[
    			'matchs' => $this->getEr("AppBundle:MatchSoccer")->findAll()
			]
		);
	}

	protected function processType(Request $request, $data, string $successMessage)
	{
		$form = $this->createForm(MatchSoccerType::class, $data)
			->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$data = $form->getData();

			if ($this->getEr("AppBundle:MatchSoccer")->matchExist($data)) {
				dump("el partido existe"); die;
			}

			$this->getEm()->persist($data);
			$this->getEm()->flush($data);

			$this->addFlash(
				"success",
				sprintf($successMessage, $data->getHome()->getName())
			);

			return $this->redirect($this->generateUrl("match_create"));
		}

		return $this->render(
			"match.html.twig",
			[
				'form'	=> $form->createView()
			]
		);
	}

	public function createAction(Request $request)
	{
		return $this->processType(
			$request,
			new \AppBundle\Entity\MatchSoccer(),
			"Se registro el partido %s para el dia :  hs."
		);
	}

	public function editAction(Request $request, $id)
	{
		if (! $match = $this->getEr("AppBundle:MatchSoccer")->findOneById($id)) {
			http_response_code(404);
			exit();
		}

		return $this->processType(
			$request,
			$match,
			"Se edito el partido %s el 00/00/0000"
		);
	}

	public function deleteAction(Request $request, $id)
	{
		if (!$match = $this->getEr("AppBundle:MatchSoccer")->findOneById($id)) {
			http_response_code(404);
			exit();
		}

		try {

			$this->getEm()->remove($match);
			$this->getEm()->flush();

			$this->addFlash(
				"success",
				"Se ha eliminado correctamente el partido {$match->getHome()->getName()} VS
				{$match->getVisitor()->getName()} para el dia: {$match->getDateMatch()->format("Y-m-d H:i:s")}"
			);

		}
		catch (\Exception $e) {
			$this->addFlash(
				"error",
				"No se pudo eliminar el partido {$team->getName()} con ID : {$id}"
			);

		}

		return $this->redirect($this->generateUrl("match_create"));
	}

	public function fixtureAction(Request $request)
	{
		$fixture = $this->getEr("AppBundle:MatchSoccer")->fixture();

		return $this->render(
			"fixture.html.twig",
			[
				"fixture" => $fixture
			]
		);
	}
}
