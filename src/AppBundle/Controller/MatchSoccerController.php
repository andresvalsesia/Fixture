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

	protected function processType(Request $request, $data, string $successMessage, string $view)
	{
		$form = $this->createForm(MatchSoccerType::class, $data)
			->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$data = $form->getData();

			if ($this->getEr("AppBundle:MatchSoccer")->matchExist($data)) {

				$this->addFlash(
				"success",
				"El partido ya existe"
				);

				return $this->redirect($this->generateUrl("match_create"));
			}

			$this->getEm()->persist($data);
			$this->getEm()->flush($data);

			$this->addFlash(
				"success",
				sprintf(
					$successMessage,
					$data->getHome()->getName(),
					$data->getVisitor()->getName()
				)
			);

			return $this->redirect($this->generateUrl("match_create"));
		}

		return $this->render(
			$view,
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
			"Se registro el partido %s vs %d",
			"match.html.twig"
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
			"Se edito el partido %s vs %d",
			"edit_match.html.twig"
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
				"Se ha eliminado correctamente el partido"
			);

		}
		catch (\Exception $e) {
			$this->addFlash(
				"error",
				"No se pudo eliminar el partido {$team->getName()} con ID : {$id}"
			);

		}

		return $this->redirect($this->generateUrl("match_fixture"));
	}

	public function fixtureAction(Request $request)
	{
		$fixture = $this->getEr("AppBundle:MatchSoccer")->fixture();
		$matchs = $this->getEr("AppBundle:MatchSoccer")->findAll();
		return $this->render(
			"fixture.html.twig",
			[
				'fixture' => $fixture,
				'matchs'  => $matchs
			]
		);
	}
}
