<?php
namespace App\Entity\Traits;
/**
 * Creation d'un trait permettant de regrouper les elements utilises dans plusieurs entity 
 * pour eviter de les reecrire à chaque besoin.
 */
trait Timestampable
{
     /**
     * @ORM\Column(type="datetime", options= {"default":"CURRENT_TIMESTAMP"})
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="datetime",  options= {"default":"CURRENT_TIMESTAMP"}))
     */
    private $dateMAJ;

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getDateMAJ(): ?\DateTimeInterface
    {
        return $this->dateMAJ;
    }

    public function setDateMAJ(\DateTimeInterface $dateMAJ): self
    {
        $this->dateMAJ = $dateMAJ;

        return $this;
    }
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function upDateTimetamps(){
        
        
        $this->setDateCreation(new \DateTimeImmutable );
        $this->setDateMAJ(new \DateTimeImmutable );
    }

    
}


?>