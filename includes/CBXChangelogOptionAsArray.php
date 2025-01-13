<?php
class CBXChangelogOptionAsArray {
	private $optionName; // The option name where the data will be saved
	private $primaryKey;
	private $data = [];
	private $nextIndex = 1;
	private $usedKeys = []; // Track all used keys

	// Constructor accepts the option name and primary key
	public function __construct($optionName = 'custom_optionname', $primaryKey = 'id') {
		$this->primaryKey = $primaryKey;
		$this->optionName = $optionName;
		$this->loadData();
	}

	// Load data from the options table
	private function loadData() {
		$savedData = get_option($this->optionName, []);
		if (is_array($savedData) && isset($savedData['data'], $savedData['nextIndex'], $savedData['usedKeys'])) {
			$this->data = $savedData['data'];
			$this->nextIndex = $savedData['nextIndex'];
			$this->usedKeys = $savedData['usedKeys'];
		}
	}

	// Save data to the options table
	private function saveData() {
		update_option($this->optionName, [
			'data' => $this->data,
			'nextIndex' => $this->nextIndex,
			'usedKeys' => $this->usedKeys
		]);
	}

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

	// Insert a new row
	public function insert($row) {
		$primaryKey = $this->primaryKey;

		// Check if the primary key field exists in the row
		if (!isset($row[$primaryKey])) {
			// Auto-generate the primary key using the next available index
			$row[$primaryKey] = $this->nextIndex;
			$this->nextIndex++; // Increment the next index
		} else {
			// Ensure the provided primary key is unique
			foreach ($this->data as $dataRow) {
				if (isset($dataRow[$primaryKey]) && $dataRow[$primaryKey] === $row[$primaryKey]) {
					throw new InvalidArgumentException("Primary key {$row[$primaryKey]} already exists."); //phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
				}
			}

			// Update nextIndex if the provided primary key is greater than it
			$this->nextIndex = max($this->nextIndex, $row[$primaryKey] + 1);
		}

		// Add the new row to the data
		$this->data[] = $row;
		$this->saveData();
	}

	// Get a row by primary key
	public function get($key) {
		$primaryKey = $this->primaryKey;

		// Search for the row with the matching primary key
		foreach ($this->data as $dataRow) {
			if (isset($dataRow[$primaryKey]) && $dataRow[$primaryKey] === $key) {
				return $dataRow;
			}
		}

		return null; // Row with primary key not found
	}

	// Update a row by primary key
	public function update($key, $row) {
		$primaryKey = $this->primaryKey;

		// Search for the row with the matching primary key
		foreach ($this->data as &$dataRow) {
			if (isset($dataRow[$primaryKey]) && $dataRow[$primaryKey] === $key) {
				// Update the row
				$dataRow = array_merge($dataRow, $row);
				$this->saveData();
				return;
			}
		}

		throw new OutOfBoundsException("Row with primary key $key does not exist."); //phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
	}

	// Delete a row by primary key
	public function delete($key) {
		$primaryKey = $this->primaryKey;

		// Search for the row with the matching primary key
		foreach ($this->data as $index => $dataRow) {
			if (isset($dataRow[$primaryKey]) && $dataRow[$primaryKey] === $key) {
				// Delete the row
				unset($this->data[$index]);
				$this->saveData();
				return;
			}
		}

		throw new OutOfBoundsException("Row with primary key $key does not exist."); //phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
	}

	// Get the total number of rows
	public function getTotalRows() {
		return count($this->data);
	}

	// Get the next row by primary key
	public function getNextRow($key) {
		$primaryKey = $this->primaryKey;
		$keys  = array_keys($this->data);
		$index = -1;

		// Find the index of the row with the matching primary key
		foreach ($keys as $i => $dataKey) {
			if ($this->data[$dataKey][$primaryKey] === $key) {
				$index = $i;
				break;
			}
		}

		// If the row is found and there is a next row
		if ($index !== -1 && isset($keys[$index + 1])) {
			return $this->data[$keys[$index + 1]];
		}

		return null; // No next row
	}

	// Get the previous row by primary key
	public function getPrevRow($key) {
		$primaryKey = $this->primaryKey;
		$keys  = array_keys($this->data);
		$index = -1;

		// Find the index of the row with the matching primary key
		foreach ($keys as $i => $dataKey) {
			if ($this->data[$dataKey][$primaryKey] === $key) {
				$index = $i;
				break;
			}
		}

		// If the row is found and there is a previous row
		if ($index !== -1 && isset($keys[$index - 1])) {
			return $this->data[$keys[$index - 1]];
		}

		return null; // No previous row
	}

	// Get all rows with optional sorting
	/*public function getAll($orderBy = null, $order = 'asc') {
		$data = $this->data;

		// Apply sorting if column is provided
		if ($orderBy !== null) {
			usort($data, function ($a, $b) use ($orderBy, $order) {
				if (!isset($a[$orderBy]) || !isset($b[$orderBy])) {
					return 0; // Skip sorting if column doesn't exist
				}
				$comparison = $a[$orderBy] <=> $b[$orderBy];

				return $order === 'desc' ? -$comparison : $comparison;
			});
		}

		return array_values($data);
	}*/

	// Get paginated rows with sorting
	/*public function getPaginatedRows($page = 1, $perPage = 10, $orderBy = null, $order = 'asc') {
		$data = $this->data;

		// Apply sorting if column is provided
		if ($orderBy !== null) {
			usort($data, function ($a, $b) use ($orderBy, $order) {
				if (!isset($a[$orderBy]) || !isset($b[$orderBy])) {
					return 0; // Skip sorting if column doesn't exist
				}
				$comparison = $a[$orderBy] <=> $b[$orderBy];

				return $order === 'desc' ? -$comparison : $comparison;
			});
		}

		// Paginate the data
		$totalRows     = count($data);
		$start         = ($page - 1) * $perPage;
		$paginatedData = array_slice($data, $start, $perPage);

		return [
			'data'        => $paginatedData,
			'totalRows'   => $totalRows,
			'currentPage' => $page,
			'perPage'     => $perPage,
			'totalPages'  => ceil($totalRows / $perPage),
		];
	}*/
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
				$isDate = ($orderBy === 'date' || preg_match('/^\d{4}-\d{2}-\d{2}$/', $a[ $orderBy ]) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $b[ $orderBy ]));

				if ( $isDate ) {
					$comparison = strtotime( $a[ $orderBy ] ) <=> strtotime( $b[ $orderBy ] );
				} else {
					$comparison = $a[ $orderBy ] <=> $b[ $orderBy ];
				}

				return $order === 'desc' ? - $comparison : $comparison;
			} );
		}
		else{
			// Sort by natural index if no column is provided
			$data = $order === 'desc' ? array_reverse( $data ) : $data;
		}

		return array_values( $data );
	}//end method getAll

	// Get paginated rows with sorting
	public function getPaginatedRows( $page = 1, $perPage = 10, $orderBy = null, $order = 'asc' ) {
		$data = $this->data;

		// Apply sorting if column is provided
		if ( $orderBy !== null ) {
			usort( $data, function ( $a, $b ) use ( $orderBy, $order ) {
				if ( ! isset( $a[ $orderBy ] ) || ! isset( $b[ $orderBy ] ) ) {
					return 0; // Skip sorting if column doesn't exist
				}

				// Check if the field is a date type
				$isDate = ($orderBy === 'date' || preg_match('/^\d{4}-\d{2}-\d{2}$/', $a[ $orderBy ]) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $b[ $orderBy ]));

				if ( $isDate ) {
					$comparison = strtotime( $a[ $orderBy ] ) <=> strtotime( $b[ $orderBy ] );
				} else {
					$comparison = $a[ $orderBy ] <=> $b[ $orderBy ];
				}

				return $order === 'desc' ? - $comparison : $comparison;
			} );
		}
		else{
			// Sort by natural index if no column is provided
			$data = $order === 'desc' ? array_reverse( $data ) : $data;
		}

		// Paginate the data
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
	}//end method getPaginatedRows

	// Set the primary key for each row based on its index, starting from 1
	public function setPrimaryKeyFromIndex() {
		foreach ($this->data as $index => &$row) {
			// Assign the primary key starting from 1
			$row[$this->primaryKey] = $index + 1;
		}
		// Save the updated data
		$this->saveData();
	}

	// Reset all rows and properties to their initial state
	public function resetRows() {
		$this->data = [];
		$this->usedKeys = [];
		$this->nextIndex = 1;
		$this->saveData();
	}//end method resetRows
}//end class CBXChangelogOptionAsArray