<?php
/**
 * Holds the Download_Link model
 *
 * @package licensehub
 */

namespace LicenseHub\Includes\Model;

use LicenseHub\Includes\Abstract\Model;
use LicenseHub\Includes\Interface\Model_Blueprint;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( '\LicenseHub\Includes\Model\Download_Link' ) ) {
	/**
	 * Model for API keys
	 */
	class Download_Link extends Model implements Model_Blueprint
	{
		/**
		 * The name of the table
		 *
		 * @var string
		 */
		protected string $table = 'download_links';

		/**
		 * Fields of the model
		 *
		 * @var array|array[]
		 */
		protected array $fields = array(
			'release_id'    => array( 'required', 'integer' ),
			'link'     		=> array( 'required', 'string' ),
			'type'    		=> array( 'string' ),
			'user_id' 		=> array( 'required', 'integer' ),
			'expires_at' 	=> array( 'date' ),
			'allowed_ips' 	=> array( 'array' ),
			'status' 		=> array( 'required', 'string' ),
			'created_at' 	=> array( 'required', 'date' ),
			'updated_at' 	=> array( 'required', 'date' ),
			'meta'		    => array( 'serialized' ),
		);

		/**
		 * The id of the release
		 *
		 * @var int
		 */
		public int $id = 0;

		/**
		 * The release id of the download link
		 *
		 * @var int
		 */
		public int $release_id = 0;

		/**
		 * The URL of the download link
		 *
		 * @var string
		 */
		public string $link = '';

		/**
		 * The type of the download link
		 *
		 * @var string
		 */
		public string $type = '';

		/**
		 * The user id of the download link
		 *
		 * @var int
		 */
		public int $user_id = 0;

		/**
		 * The expiration date of the download link
		 *
		 * @var string
		 */
		public string $expires_at = '';

		/**
		 * The allowed IPs of the download link
		 *
		 * @var array
		 */
		public array $allowed_ips = array();

		/**
		 * The status of the download link
		 *
		 * @var string
		 */
		public string $status = '';

		/**
		 * The date the download link was created at
		 *
		 * @var string
		 */
		public string $created_at = '';

		/**
		 * The date the download link was last updated at
		 *
		 * @var string
		 */
		public string $updated_at = '';

		/**
		 * The meta fields of the download link
		 *
		 * @var mixed
		 */
		public mixed $meta = array();

		/**
		 * Init the database table
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
                    release_id mediumint(9) NOT NULL,
                    user_id mediumint(9),
                    link varchar(255) NOT NULL,
                    type varchar(255) NOT NULL,
                    allowed_ips TEXT,
                    status varchar(255),
                    expires_at datetime,
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
