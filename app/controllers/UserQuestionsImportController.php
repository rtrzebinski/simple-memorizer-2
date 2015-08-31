<?php

/**
 * Import user questions from CSV file
 */
class UserQuestionsImportController extends BaseController {

	/**
	 * @var UserQuestionRepository 
	 */
	private $repository;

	public function __construct(UserQuestionRepository $repository)
	{
		$this->repository = $repository;
	}

	/**
	 * Display user questions import web interface
	 */
	public function index()
	{
		return View::make('user_questions_import');
	}

	/**
	 * Import user questions from CSV file, and redirect to questions user interface
	 * @throws Exception
	 */
	public function import()
	{
		// Symfony\Component\HttpFoundation\File\UploadedFile
		$fileInfo = Input::file('csv_file');

		// check if file is valid
		if (!$fileInfo->isValid() || $fileInfo->getClientMimeType() != "text/csv")
		{
			// throw exception if file is not valid
			throw new Exception('Uploaded file is not valid');
		}

		// open file, type of $file is SplFileObject
		$file = $fileInfo->openFile();

		// Read file lines as CSV rows
		$file->setFlags(SplFileObject::READ_CSV);

		// TODO add db transaction here
		foreach ($file as $key => $value)
		{
			// skip header (first) line of the file
			if ($key == '0')
			{
				continue;
			}

			// skip not correct lines, valid line must have 5 fields
			if (count($value) != 5)
			{
				continue;
			}

			// create new user question from CSV file row
			if (Input::has('reset_number_of_answers'))
			{
				// don't include number of answers from CSV file
				$this->repository->create($value[0], $value[1]);
			}
			else
			{
				// include number of answers from CSV file
				$this->repository->create($value[0], $value[1], $value[2], $value[3], $value[4]);
			}
		}

		// redirect to questions interface
		return Redirect::route('questions');
	}

}
