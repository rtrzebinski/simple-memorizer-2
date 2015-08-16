<?php

/**
 * Builds a CSV file from given data
 * 
 * Creates file in temporary folder, so it doesn't have to be manually deleted
 */
class CsvBuilder {

	/**
	 * CSV field structure
	 * 
	 * For each element:
	 * key - header line name for field
	 * value - field name that will be used to extract field data
	 * 
	 * @var array
	 */
	private $fields = [];

	/**
	 * Data to be converted into CSV file
	 * 
	 * @var mixed Array or Traversable object 
	 */
	private $data = [];

	/**
	 * CSV file handler
	 * 
	 * @var resource 
	 */
	private $fileHandler;

	/**
	 * Constructor is private.
	 * Use create() method to get new instance of this class.
	 */
	private function __construct()
	{
		// Create temporary file to write to.
		$tmpName = tempnam(sys_get_temp_dir(), 'data');
		$this->fileHandler = fopen($tmpName, 'w');
		if (!$this->fileHandler)
		{
			throw new Exception("Can't create temporary file");
		}
	}

	/**
	 * Create new instance of this class.
	 * @return \CsvBuilder
	 */
	public static function create()
	{
		return new CsvBuilder();
	}

	/**
	 * Set CSV header line field.
	 * @param string $header Header line name for field.
	 * @param string $name Field name that will be used to extract field data from input collection.
	 * @return \CsvBuilder
	 */
	public function setHeaderField($header, $name)
	{
		$this->fields[$header] = $name;
		return $this;
	}

	/**
	 * Set data to be converted into CSV file.
	 * @param $data
	 * @return \CsvBuilder
	 */
	public function setData($data)
	{
		$this->data = $data;
		return $this;
	}

	/**
	 * Write file content.
	 * @return \CsvBuilder
	 * @throws Exception
	 */
	public function build()
	{
		if (!$this->fields)
		{
			throw new Exception('You must set at least one header field.');
		}
		if (!$this->data)
		{
			throw new Exception('You must set data collection.');
		}

		// Truncate file before writing to it, so object can be reused for different data sets
		ftruncate($this->fileHandler, 0);

		// Write CSV headers to output stream
		$this->appendHeader($this->fileHandler);

		//  Write CSV data rows to output stream
		$this->appendData($this->fileHandler);

		return $this;
	}

	/**
	 * Get generated CSV file path.
	 */
	public function getPath()
	{
		return stream_get_meta_data($this->fileHandler)['uri'];
	}

	/**
	 * Write header row into CSV file.
	 */
	private function appendHeader()
	{
		fputcsv($this->fileHandler, array_keys($this->fields));
	}

	/**
	 * Write data rows to CSV file.
	 * 
	 * Iterate over 'data' collection and write needed fields into CSV file.
	 */
	private function appendData()
	{
		foreach ($this->data as $inputDataRow)
		{
			$csvFileRowData = [];
			foreach (array_values($this->fields) as $field)
			{
				$csvFileRowData[] = $this->extractDataRowValue($inputDataRow, $field);
			}
			fputcsv($this->fileHandler, $csvFileRowData);
		}
	}

	/**
	 * Extract data row value depending on $data type.
	 * @param object|array $data
	 * @param string $field Field to be extracted
	 * @return string
	 * @throws Exception
	 */
	private function extractDataRowValue($data, $field)
	{
		if (is_object($data))
		{
			return $data->{$field};
		}
		else if (is_array($data))
		{
			return $data[$field];
		}
		else
		{
			throw new Exception('Unable to access input data row');
		}
	}

}
