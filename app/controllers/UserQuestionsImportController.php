<?php

/**
 * Import user questions from CSV file
 */
class UserQuestionsImportController extends BaseController {

	/**
	 * Display user questions import web interface
	 */
	public function displayUserQuestionsImportPage()
	{
		return View::make('user_questions_import');
	}

	/**
	 * Import user questions from CSV file, and redirect to questions user interface
	 * @throws Exception
	 */
	public function importUserQuestionsFromCsv()
	{
		// Symfony\Component\HttpFoundation\File\UploadedFile
		$fileInfo = Input::file('csv_file');

		// check if file is valid
		if (!isset($fileInfo) || !$fileInfo->isValid() || $fileInfo->getClientMimeType() != "text/csv")
		{
			$this->viewData['errors'] = new Illuminate\Support\MessageBag([Lang::get('messages.import_file_not_valid')]);
			return View::make('user_questions_import', $this->viewData);
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
				$apiCreateUserQuestionResponse = $this->apiDispatcher->callApiRoute('api_create_user_question', [
					'auth_token' => $this->apiAuthToken,
					'question' => $value[0],
					'answer' => $value[1],
				]);
			}
			else
			{
				// include number of answers from CSV file
				$apiCreateUserQuestionResponse = $this->apiDispatcher->callApiRoute('api_create_user_question', [
					'auth_token' => $this->apiAuthToken,
					'question' => $value[0],
					'answer' => $value[1],
					'number_of_good_answers' => $value[2],
					'number_of_bad_answers' => $value[3],
					'percent_of_good_answers' => $value[4],
				]);
			}

			if (!$apiCreateUserQuestionResponse->getSuccess())
			{
				// unexpected API resppnse
				throw new Exception('Unexpected API response');
			}
		}

		// redirect to questions interface
		return Redirect::route('display_user_questions');
	}

}
