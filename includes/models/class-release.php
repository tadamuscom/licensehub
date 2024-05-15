<?php
/**
 * Holds the Release model
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Model;

use LicenseHub\Includes\Abstract\Model;
use LicenseHub\Includes\Interface\Model_Blueprint;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( '\LicenseHub\Includes\Model\Release' ) ) {
	/**
	 * Model for API keys
	 */
	class Release extends Model implements Model_Blueprint
	{
		/**
		 * The name of the table
		 *
		 * @var string
		 */
		protected string $table = 'releases';

		/**
		 * Fields of the model
		 *
		 * @var array|array[]
		 */
		protected array $fields = array(
			'product_id'    => array( 'required', 'int' ),
			'version'     	=> array( 'required', 'string' ),
			'changelog'    	=> array( 'text' ),
			'created_at' 		=> array( 'required', 'date' ),
			'updated_at' 		=> array( 'required', 'date' ),
			'meta'					=> array( 'serialized' ),
		);

		/**
		 * The id of the release
		 *
		 * @var int
		 */
		public int $id;

		/**
		 * The product id of the release
		 *
		 * @var int
		 */
		public int $product_id;

		/**
		 * The version of the release
		 *
		 * @var string
		 */
		public string $version;

		/**
		 * The changelog of the release
		 *
		 * @var string
		 */
		public string $changelog;

		/**
		 * The date the release was created at
		 *
		 * @var string
		 */
		public string $created_at;

		/**
		 * The date the release was last updated at
		 *
		 * @var string
		 */
		public string $updated_at;

		/**
		 * The meta fields of the release
		 *
		 * @var array
		 */
		public array $meta;

		/**
		 * Init the database table
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function init(): void
		{
			if ( ! $this->table_exists( $this->table ) ) {
				$this->create_table(
					$this->generate_table_name( $this->table ),
					"
                    id mediumint(9) NOT NULL AUTO_INCREMENT,
                    product_id integer NOT NULL,
                    version varchar(255) NOT NULL,
                    changelog TEXT,
                    created_at datetime NOT NULL,
                    updated_at datetime NOT NULL,
                    meta TEXT,
                    PRIMARY KEY  (id)
                "
				);
			}
		}
	}
}
