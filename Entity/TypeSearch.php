<?php

namespace App\Entity;

class TypeSearch
{

   private $Type;



   
   public function getType(): ?string
   {
       return $this->Type;
   }

   public function setType(string $Type): self
   {
       $this->Type = $Type;

       return $this;
   }
}
