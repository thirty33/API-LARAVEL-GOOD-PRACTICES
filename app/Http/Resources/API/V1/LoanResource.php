<?php

namespace App\Http\Resources\API\V1;

use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Loan
 */
class LoanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'book' => new BookResource($this->whenLoaded('book')),
            'loaned_at' => $this->loaned_at,
            'returned_at' => $this->returned_at,
            'due_date' => $this->due_date,
            'returned' => $this->returned,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
