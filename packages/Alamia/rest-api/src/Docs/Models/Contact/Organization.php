<?php

namespace Alamia\RestApi\Docs\Models\Contact;

/**
 * @OA\Schema(
 *     title="Organization",
 *     description="Organization Model",
 * )
 */
class Organization
{
    /**
     * @OA\Property(
     *     title="ID",
     *     description="Organization ID",
     *     format="int64",
     *     example="1"
     * )
     *
     * @var string
     */
    private $id;

    /**
     * @OA\Property(
     *     property="name",
     *     type="string",
     *     description="Organization Name",
     *     example="Organization Name"
     * ),
     *
     * @var string
     */
    private $name;

    /**
     * @OA\Property(
     *     property="address",
     *     type="object",
     *     description="Organization Address",
     *     @OA\Property(property="address", type="string", example="123 Business St"),
     *     @OA\Property(property="city", type="string", example="New York"),
     *     @OA\Property(property="state", type="string", example="NY"),
     *     @OA\Property(property="country", type="string", example="US"),
     *     @OA\Property(property="postcode", type="string", example="10001")
     * )
     */
    private $address;

    /**
     * @OA\Property(
     *     title="Created at",
     *     description="Created at",
     *     example="2020-01-27 17:50:45",
     *     format="datetime",
     *     type="string"
     * )
     *
     * @var \DateTime
     */
    private $created_at;

    /**
     * @OA\Property(
     *     title="Updated at",
     *     description="Updated at",
     *     example="2020-01-27 17:50:45",
     *     format="datetime",
     *     type="string"
     * )
     *
     * @var \DateTime
     */
    private $updated_at;
}
