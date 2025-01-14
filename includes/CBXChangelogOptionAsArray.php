<?php

class CBXChangelogOptionAsArray {
	private $optionName;
	private $primaryKey;
	private $data = [];
	private $nextIndex = 1;
	private $usedKeys = [];

	public function __construct( $optionName = 'custom_optionname', $primaryKey = 'id' ) {
		$this->primaryKey = $primaryKey;
		$this->optionName = $optionName;
		$this->loadData();
	}

	private function loadData() {
		$savedData = get_option( $this->optionName, [] );
		if (
			is_array( $savedData ) &&
			isset( $savedData['data'], $savedData['nextIndex'], $savedData['usedKeys'] )
		) {
			$this->data      = $savedData['data'];
			$this->nextIndex = $savedData['nextIndex'];
			$this->usedKeys  = $savedData['usedKeys'];
		}
	}

	private function saveData() {
		update_option( $this->optionName, [
			'data'      => $this->data,
			'nextIndex' => $this->nextIndex,
			'usedKeys'  => $this->usedKeys
		] );
	}

	public function getUsedKeys() {
		return $this->usedKeys;
	}

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
			$nextIndex = cbxchangelog_custom_max($this->usedKeys) + 1;
		}

		$this->nextIndex = $nextIndex;
		return $nextIndex;
	}//end method getNextIndex


	public function insert_backup( $row ) {
		$primaryKey = $this->primaryKey;

		if ( ! isset( $row[ $primaryKey ] ) ) {
			do {
				$row[ $primaryKey ] = $this->nextIndex;
				$this->nextIndex ++;
			} while ( in_array( $row[ $primaryKey ], $this->usedKeys, true ) );
		} else {
			if ( in_array( $row[ $primaryKey ], $this->usedKeys, true ) ) {
				throw new InvalidArgumentException( "Primary key {$row[$primaryKey]} already exists." );
			}

			$this->nextIndex = cbxchangelog_custom_max( $this->nextIndex, $row[ $primaryKey ] + 1 );
		}

		$this->usedKeys[] = $row[ $primaryKey ];
		$this->data[]     = $row;
		$this->saveData();
	}//end method insert_backup

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
			$this->nextIndex = cbxchangelog_custom_max( $this->nextIndex, $row[ $primaryKey ] + 1 );
		}

		// Add the new key to usedKeys if not already present
		if ( ! in_array( $row[ $primaryKey ], $this->usedKeys, true ) ) {
			$this->usedKeys[] = $row[ $primaryKey ];
		}

		// Add the new row to the data
		$this->data[] = $row;
		$this->saveData();
	}//end method insert

	public function get( $key ) {
		foreach ( $this->data as $dataRow ) {
			if ( $dataRow[ $this->primaryKey ] === $key ) {
				return $dataRow;
			}
		}

		return null;
	}

	public function update( $key, $row ) {
		foreach ( $this->data as &$dataRow ) {
			if ( $dataRow[ $this->primaryKey ] === $key ) {
				$dataRow = array_merge( $dataRow, $row );
				$this->saveData();

				return;
			}
		}

		throw new OutOfBoundsException( "Row with primary key $key does not exist." );
	}

	public function delete( $key ) {
		foreach ( $this->data as $index => $dataRow ) {
			if ( $dataRow[ $this->primaryKey ] === $key ) {
				unset( $this->data[ $index ] );
				$this->data     = array_values( $this->data );
				$this->usedKeys = array_diff( $this->usedKeys, [ $key ] );
				$this->saveData();

				return;
			}
		}

		throw new OutOfBoundsException( "Row with primary key $key does not exist." );
	}

	public function getTotalRows() {
		return count( $this->data );
	}

	public function getNextRow( $key ) {
		$keys  = array_column( $this->data, $this->primaryKey );
		$index = array_search( $key, $keys, true );

		if ( $index !== false && isset( $this->data[ $index + 1 ] ) ) {
			return $this->data[ $index + 1 ];
		}

		return null;
	}

	public function getPrevRow( $key ) {
		$keys  = array_column( $this->data, $this->primaryKey );
		$index = array_search( $key, $keys, true );

		if ( $index !== false && $index > 0 ) {
			return $this->data[ $index - 1 ];
		}

		return null;
	}

	public function getAll( $orderBy = null, $order = 'asc' ) {
		$data = $this->data;

		if ( $orderBy !== null ) {
			usort( $data, function ( $a, $b ) use ( $orderBy, $order ) {
				if ( ! isset( $a[ $orderBy ] ) || ! isset( $b[ $orderBy ] ) ) {
					return 0;
				}

				$isDate = ( $orderBy === 'date' || preg_match( '/^\d{4}-\d{2}-\d{2}$/', $a[ $orderBy ] ) && preg_match( '/^\d{4}-\d{2}-\d{2}$/', $b[ $orderBy ] ) );

				$comparison = $isDate
					? strtotime( $a[ $orderBy ] ) <=> strtotime( $b[ $orderBy ] )
					: $a[ $orderBy ] <=> $b[ $orderBy ];

				return $order === 'desc' ? - $comparison : $comparison;
			} );
		} else {
			$data = $order === 'desc' ? array_reverse( $data ) : $data;
		}

		return array_values( $data );
	}

	public function getPaginatedRows( $page = 1, $perPage = 10, $orderBy = null, $order = 'asc' ) {
		$data = $this->getAll( $orderBy, $order );

		$totalRows     = count( $data );
		$start         = ( $page - 1 ) * $perPage;
		$paginatedData = array_slice( $data, $start, $perPage );

		return [
			'data'        => $paginatedData,
			'totalRows'   => $totalRows,
			'currentPage' => $page,
			'perPage'     => $perPage,
			'totalPages'  => ceil( $totalRows / $perPage ),
		];
	}
	public function resetRows() {
		$this->data      = [];
		$this->usedKeys  = [];
		$this->nextIndex = 1;
		//$this->saveData();
	}//end method resetRows

	// Reset all rows and properties to their initial state and save
	public function resetRowsAndSave() {
		$this->data      = [];
		$this->usedKeys  = [];
		$this->nextIndex = 1;
		$this->saveData();
	}//end method resetRowsAndSave

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
		$this->nextIndex = ! empty( $this->usedKeys ) ? cbxchangelog_custom_max( $this->usedKeys ) + 1 : 1;

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

	// Synchronize the primary key with the row index in reverse order
	public function syncPrimaryKeyWithIndexReverse() {
		$this->usedKeys = []; // Reset the usedKeys array

		$totalRows = count($this->data); // Get the total number of rows

		foreach ($this->data as $index => &$row) {
			// Set the primary key to the reverse index (totalRows - index)
			$row[$this->primaryKey] = $totalRows - $index;

			// Update the usedKeys array
			$this->usedKeys[] = $row[$this->primaryKey];
		}

		// Update nextIndex to the highest primary key + 1
		$this->nextIndex = $totalRows + 1;

		// Save the updated data and usedKeys
		$this->saveData();
	}//end method syncPrimaryKeyWithIndexReverse
}//end class CBXChangelogOptionAsArray