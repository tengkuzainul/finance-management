<?php

namespace App\Traits;

use Vinkla\Hashids\Facades\Hashids;

trait HasHashId
{
   /**
    * Get the hashed ID attribute.
    */
   public function getHashIdAttribute(): string
   {
      return Hashids::encode($this->id);
   }

   /**
    * Get the route key for the model.
    */
   public function getRouteKey(): string
   {
      return Hashids::encode($this->getKey());
   }

   /**
    * Get the route key name for Laravel route model binding.
    */
   public function getRouteKeyName(): string
   {
      return 'id';
   }

   /**
    * Resolve the route binding.
    */
   public function resolveRouteBinding($value, $field = null)
   {
      $decoded = Hashids::decode($value);

      if (empty($decoded)) {
         abort(404);
      }

      return $this->where($field ?? $this->getRouteKeyName(), $decoded[0])->firstOrFail();
   }

   /**
    * Encode an ID to hash.
    */
   public static function encodeId($id): string
   {
      return Hashids::encode($id);
   }

   /**
    * Decode a hash to ID.
    */
   public static function decodeId($hash): ?int
   {
      $decoded = Hashids::decode($hash);
      return $decoded[0] ?? null;
   }
}
