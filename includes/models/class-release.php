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
	class Release extends Model implements Model_Blueprint {

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
			'product_id'    => array( 'required', 'integer' ),
			'version'       => array( 'required', 'string', 'unique' ),
			'changelog'     => array( 'string' ),
			'created_at'    => array( 'required', 'date' ),
			'updated_at'    => array( 'required', 'date' ),
			'meta'          => array(),
			'attachment_id' => array(),
		);

		/**
		 * The id of the release
		 *
		 * @var int
		 */
		public int $id = 0;

		/**
		 * The product id of the release
		 *
		 * @var int
		 */
		public int $product_id = 0;

		/**
		 * The version of the release
		 *
		 * @var string
		 */
		public string $version = '';

		/**
		 * The changelog of the release
		 *
		 * @var string
		 */
		public string $changelog = '';

		/**
		 * The date the release was created at
		 *
		 * @var string
		 */
		public string $created_at = '';

		/**
		 * The date the release was last updated at
		 *
		 * @var string
		 */
		public string $updated_at = '';

		/**
		 * The meta fields of the release
		 *
		 * @var mixed
		 */
		public mixed $meta = array();

		/**
		 * The ID of the attachment
		 *
		 * @var mixed
		 */
		public mixed $attachment_id = 0;

		/**
		 * Init the database table
		 *
		 * @return void
		 * @since 1.0.0
		 *
		 */
		public function init(): void {
			if ( ! $this->table_exists( $this->table ) ) {
				$this->create_table(
					$this->generate_table_name( $this->table ),
					'
                    id mediumint(9) NOT NULL AUTO_INCREMENT,
                    product_id mediumint(9) NOT NULL,
                    version varchar(255) NOT NULL,
                    attachment_id varchar(255),
                    changelog TEXT,
                    created_at datetime NOT NULL,
                    updated_at datetime NOT NULL,
                    meta TEXT,
                    PRIMARY KEY  (id)
                '
				);
			}
		}
	}
}
