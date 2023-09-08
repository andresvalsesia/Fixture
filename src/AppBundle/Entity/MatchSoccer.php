<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MatchSoccer
 *
 * @ORM\Table(name="match_soccer")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MatchSoccerRepository")
 */
class MatchSoccer
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumn(name="home_id", referencedColumnName="id")
     */
    private $home;

    /**
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumn(name="visitor_id", referencedColumnName="id")
     */
    private $visitor;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datematch", type="datetime")
     */
    private $datematch;

    /**
     * @var int
     *
     * @ORM\Column(name="goalsHome", type="integer", nullable=true)
     */
    private $goalsHome;

    /**
     * @var int
     *
     * @ORM\Column(name="goalsVisitor", type="integer", nullable=true)
     */
    private $goalsVisitor;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set home
     *
     * @param string $home
     *
     * @return MatchSoccer
     */
    public function setHome($home)
    {
        $this->home = $home;

        return $this;
    }

    /**
     * Get home
     *
     * @return string
     */
    public function getHome()
    {
        return $this->home;
    }

    /**
     * Set visitor
     *
     * @param string $visitor
     *
     * @return MatchSoccer
     */
    public function setVisitor($visitor)
    {
        $this->visitor = $visitor;

        return $this;
    }

    /**
     * Get visitor
     *
     * @return string
     */
    public function getVisitor()
    {
        return $this->visitor;
    }

    /**
     * Set datematch
     *
     * @param \DateTime $datematch
     *
     * @return MatchSoccer
     */
    public function setDatematch($datematch)
    {
        $this->datematch = $datematch;

        return $this;
    }

    /**
     * Get datematch
     *
     * @return \DateTime
     */
    public function getDatematch()
    {
        return $this->datematch;
    }

    /**
     * Set goalsHome
     *
     * @param integer $goalsHome
     *
     * @return MatchSoccer
     */
    public function setGoalsHome($goalsHome)
    {
        $this->goalsHome = $goalsHome;

        return $this;
    }

    /**
     * Get goalsHome
     *
     * @return int
     */
    public function getGoalsHome()
    {
        return $this->goalsHome;
    }

    /**
     * Set goalsVisitor
     *
     * @param integer $goalsVisitor
     *
     * @return MatchSoccer
     */
    public function setGoalsVisitor($goalsVisitor)
    {
        $this->goalsVisitor = $goalsVisitor;

        return $this;
    }

    /**
     * Get goalsVisitor
     *
     * @return int
     */
    public function getGoalsVisitor()
    {
        return $this->goalsVisitor;
    }
}

