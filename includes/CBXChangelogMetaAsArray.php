<?php

class CBXChangelogMetaAsArray {
	private $postId; // The post ID to which meta data will be saved
	private $primaryKey;
	private $metakey; // Meta key for saving and loading data
	private $data = [];
	private $nextIndex = 1;
	private $usedKeys = []; // Track all used keys

	public function __construct( $postId, $metakey = '_custom_metafield', $primaryKey = 'id' ) {
		$this->postId     = $postId;
		$this->primaryKey = $primaryKey;
		$this->metakey    = $metakey; // Assign metakey from the constructor
		$this->loadData();
	}//end constructor

	// Load data from post meta using the provided meta key
	private function loadData() {
		$savedData = get_post_meta( $this->postId, $this->metakey, true ); // Use the metakey here
		if ( is_array( $savedData ) && isset( $savedData['data'], $savedData['nextIndex'], $savedData['usedKeys'] ) ) {
			$this->data      = $savedData['data'];
			$this->nextIndex = $savedData['nextIndex'];
			$this->usedKeys  = $savedData['usedKeys'];
		}
	}//end method loadData

	// Save data to post meta using the provided meta key
	private function saveData() {
		update_post_meta( $this->postId, $this->metakey, [ // Use the metakey here
			'data'      => $this->data,
			'nextIndex' => $this->nextIndex,
			'usedKeys'  => $this->usedKeys
		] );
	}//end method saveData

	/**
	 * Returns the usedKeys
	 *
	 * @return array
	 */
	public function getUsedKeys() {
		return $this->usedKeys;
	}//end method getUsedKeys

	/**
	 * Returns the nextIndex
	 *
	 * @return int
	 */
	public function getNextIndex() {
		return $this->nextIndex;
	}//end method getNextIndex

	/**
	 * Returns the nextIndex
	 *
	 * @return int
	 */
	public function settNextIndex($nextIndex) {
		//don't allow already used keys as nextIndex
		if(in_array($nextIndex, $this->usedKeys)){
			$nextIndex = max($this->usedKeys) + 1;
		}

		$this->nextIndex = $nextIndex;
		return $nextIndex;
	}//end method getNextIndex

	// Insert a new row
	public function insert_backup( $row ) {
		$primaryKey = $this->primaryKey;

		// Check if the primary key field exists in the row
		if ( ! isset( $row[ $primaryKey ] ) ) {
			// Auto-generate the primary key using the next available index
			$row[ $primaryKey ] = $this->nextIndex;
			$this->nextIndex ++; // Increment the next index
		} else {
			// Ensure the provided primary key is unique
			foreach ( $this->data as $dataRow ) {
				if ( isset( $dataRow[ $primaryKey ] ) && $dataRow[ $primaryKey ] === $row[ $primaryKey ] ) {
					throw new InvalidArgumentException( "Primary key {$row[$primaryKey]} already exists." );
				}
			}

			// Update nextIndex if the provided primary key is greater than it
			$this->nextIndex = max( $this->nextIndex, $row[ $primaryKey ] + 1 );
		}

		// Add the new key to usedKeys if not already present
		if ( ! in_array( $row[ $primaryKey ], $this->usedKeys, true ) ) {
			$this->usedKeys[] = $row[ $primaryKey ];
		}

		// Add the new row to the data
		$this->data[] = $row;
		$this->saveData();
	}

	// Insert a new row or update if primary key exists
	public function insert( $row ) {
		$primaryKey = $this->primaryKey;

		// Check if the primary key field exists in the row
		if ( ! isset( $row[ $primaryKey ] ) ) {
			// Auto-generate the primary key using the next available index
			$row[ $primaryKey ] = $this->nextIndex;
			$this->nextIndex ++; // Increment the next index
		} else {
			// Check if a row with the given primary key already exists
			foreach ( $this->data as $dataRow ) {
				if ( isset( $dataRow[ $primaryKey ] ) && $dataRow[ $primaryKey ] === $row[ $primaryKey ] ) {
					// Update the existing row
					$this->update( $row[ $primaryKey ], $row );

					return;
				}
			}

			// Update nextIndex if the provided primary key is greater than it
			$this->nextIndex = max( $this->nextIndex, $row[ $primaryKey ] + 1 );
		}

		// Add the new key to usedKeys if not already present
		if ( ! in_array( $row[ $primaryKey ], $this->usedKeys, true ) ) {
			$this->usedKeys[] = $row[ $primaryKey ];
		}

		// Add the new row to the data
		$this->data[] = $row;
		$this->saveData();
	}//end method insert


	// Get a row by primary key
	public function get( $key ) {
		$primaryKey = $this->primaryKey;

		// Search for the row with the matching primary key
		foreach ( $this->data as $dataRow ) {
			if ( isset( $dataRow[ $primaryKey ] ) && $dataRow[ $primaryKey ] === $key ) {
				return $dataRow;
			}
		}

		return null; // Row with primary key not found
	}//end method get

	// Update a row by primary key
	public function update( $key, $row ) {
		$primaryKey = $this->primaryKey;

		// Search for the row with the matching primary key
		foreach ( $this->data as &$dataRow ) {
			if ( isset( $dataRow[ $primaryKey ] ) && $dataRow[ $primaryKey ] === $key ) {
				// If the primary key changes, update usedKeys
				if ( isset( $row[ $primaryKey ] ) && $row[ $primaryKey ] !== $key ) {
					if ( ( $keyIndex = array_search( $key, $this->usedKeys, true ) ) !== false ) {
						unset( $this->usedKeys[ $keyIndex ] );
					}
					$this->usedKeys[] = $row[ $primaryKey ];
				}

				// Update the row
				$dataRow = array_merge( $dataRow, $row );
				$this->saveData();

				return;
			}
		}

		// If no row with the matching primary key was found, throw an exception
		throw new OutOfBoundsException( "Row with primary key $key does not exist." );
	}

	// Delete a row by primary key
	public function delete( $key ) {
		$primaryKey = $this->primaryKey;

		// Search for the row with the matching primary key
		foreach ( $this->data as $index => $dataRow ) {
			if ( isset( $dataRow[ $primaryKey ] ) && $dataRow[ $primaryKey ] === $key ) {
				// Delete the row
				unset( $this->data[ $index ] );

				// Remove the key from usedKeys
				if ( ( $keyIndex = array_search( $key, $this->usedKeys, true ) ) !== false ) {
					unset( $this->usedKeys[ $keyIndex ] );
					$this->usedKeys = array_values( $this->usedKeys ); // Reindex array
				}

				$this->saveData();

				return;
			}
		}

		throw new OutOfBoundsException( "Row with primary key $key does not exist." );
	}//end method delete

	// Get all rows with optional sorting
	public function getAll( $orderBy = null, $order = 'asc' ) {
		$data = $this->data;

		// Apply sorting if column is provided
		if ( $orderBy !== null ) {
			usort( $data, function ( $a, $b ) use ( $orderBy, $order ) {
				if ( ! isset( $a[ $orderBy ] ) || ! isset( $b[ $orderBy ] ) ) {
					return 0; // Skip sorting if column doesn't exist
				}

				// Check if the field is a date type
				$isDate = ( $orderBy === 'date' || preg_match( '/^\d{4}-\d{2}-\d{2}$/', $a[ $orderBy ] ) && preg_match( '/^\d{4}-\d{2}-\d{2}$/', $b[ $orderBy ] ) );

				if ( $isDate ) {
					$comparison = strtotime( $a[ $orderBy ] ) <=> strtotime( $b[ $orderBy ] );
				} else {
					$comparison = $a[ $orderBy ] <=> $b[ $orderBy ];
				}

				return $order === 'desc' ? - $comparison : $comparison;
			} );
		} else {
			// Sort by natural index if no column is provided
			$data = $order === 'desc' ? array_reverse( $data ) : $data;
		}

		return array_values( $data );
	}//end method getAll


	// Reset all rows and properties to their initial state
	public function resetRows() {
		$this->data      = [];
		$this->usedKeys  = [];
		$this->nextIndex = 1;
		//$this->saveData();
	}//end method resetRows\

	// Reindex the usedKeys array based on the primary keys of all rows
	public function reindexUsedKeys() {
		$primaryKey = $this->primaryKey;

		// Reset usedKeys
		$this->usedKeys = [];

		// Iterate over the data and add primary keys to usedKeys
		foreach ( $this->data as $row ) {
			if ( isset( $row[ $primaryKey ] ) ) {
				$this->usedKeys[] = $row[ $primaryKey ];
			}
		}

		// Update nextIndex as max of usedKeys + 1 or reset to 1 if no keys are present
		$this->nextIndex = ! empty( $this->usedKeys ) ? max( $this->usedKeys ) + 1 : 1;

		// Save the updated data and usedKeys
		$this->saveData();
	}//end method reindexUsedKeys

	// Synchronize the primary key with the row index
	public function syncPrimaryKeyWithIndex() {
		$this->usedKeys = []; // Reset the usedKeys array

		foreach ( $this->data as $index => &$row ) {
			// Set the primary key to index + 1
			$row[ $this->primaryKey ] = $index + 1;

			// Update the usedKeys array
			$this->usedKeys[] = $row[ $this->primaryKey ];
		}

		// Update nextIndex to the last index + 2 (one greater than the highest key)
		$this->nextIndex = count( $this->data ) + 1;

		// Save the updated data and usedKeys
		$this->saveData();
	}//end method syncPrimaryKeyWithIndex

}//end class CBXChangelogMetaAsArray