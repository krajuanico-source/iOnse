<?php

namespace App\Http\Controllers;

use App\Models\GadResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GadResponseController extends Controller
{
  /**
   * Display a listing of GAD profiles.
   */
  public function index()
  {
    $profiles = GadResponse::latest()->paginate(20);
    return view('forms.gad_profile.index', compact('profiles'));
  }

  /**
   * Show form to create a new GAD profile.
   */
  public function create()
  {
    $row = new GadResponse(); // empty model for defaults
    $savedChallenges = [];
    $savedTrainings = [];
    $savedInterventions = [];

    // Questions for the form
    $questions = $this->getQuestions();

    // GAD options
    $gadChallenges = $this->getGadChallenges();
    $gadTrainings = $this->getGadTrainings();
    $interventionModes = $this->getInterventionModes();

    return view('forms.gad_profile.create', compact(
      'row',
      'savedChallenges',
      'savedTrainings',
      'savedInterventions',
      'questions',
      'gadChallenges',
      'gadTrainings',
      'interventionModes'
    ));
  }

  /**
   * Store a newly created GAD profile.
   */
  public function store(Request $request)
  {
    $data = $this->prepareData($request);

    // Get employee_id of logged-in user
    $employeeId = Auth::user()->employee_id ?? null;
    if (!$employeeId) {
      return redirect()->back()->with('error', 'Your employee ID is missing.');
    }

    $data['user_id'] = $employeeId;

    // Check if a record already exists for this employee
    $existing = GadResponse::where('user_id', $employeeId)->first();

    if ($existing) {
      // Update the existing record
      $existing->update($data);
      $message = 'GAD Profile updated successfully.';
    } else {
      // Create a new record
      GadResponse::create($data);
      $message = 'GAD Profile saved successfully.';
    }

    return redirect()
      ->route('forms.gad_profile.create')
      ->with('success', $message);
  }

  /**
   * Show a single GAD profile.
   */
  public function show($id)
  {
    $profile = GadResponse::findOrFail($id);
    return view('forms.gad_profile.show', compact('profile'));
  }

  /**
   * Show form to edit an existing GAD profile.
   */
  public function edit($id)
  {
    $row = GadResponse::findOrFail($id);

    // Decode JSON fields for checkboxes
    $savedChallenges = json_decode($row->gad_challenges ?? '[]', true);
    $savedTrainings = json_decode($row->gad_trainings ?? '[]', true);
    $savedInterventions = json_decode($row->desired_mode ?? '[]', true);

    $questions = $this->getQuestions();
    $gadChallenges = $this->getGadChallenges();
    $gadTrainings = $this->getGadTrainings();
    $interventionModes = $this->getInterventionModes();

    return view('forms.gad_profile.create', compact(
      'row',
      'savedChallenges',
      'savedTrainings',
      'savedInterventions',
      'questions',
      'gadChallenges',
      'gadTrainings',
      'interventionModes'
    ));
  }

  /**
   * Update an existing GAD profile.
   */
  public function update(Request $request, $id)
  {
    $profile = GadResponse::findOrFail($id);
    $data = $this->prepareData($request);

    $profile->update($data);

    return redirect()
      ->route('forms.gad_profile.index')
      ->with('success', 'GAD Profile updated successfully.');
  }

  /**
   * Delete a GAD profile.
   */
  public function destroy($id)
  {
    GadResponse::destroy($id);
    return redirect()
      ->route('forms.gad_profile.index')
      ->with('success', 'GAD Profile deleted successfully.');
  }

  /**
   * Prepare form data for saving (handles JSON and 'Other' fields)
   */
  private function prepareData(Request $request)
  {
    $data = $request->all();

    // Assign empid from the logged-in user's employee_id
    $data['user_id'] = Auth::user()->employee_id ?? null;

    // Honorific / Other
    $data['honorifics'] = ($request->honorific ?? '') === 'Other'
      ? $request->honorific_other
      : $request->honorific;

    // Gender / Other
    $data['gender'] = ($request->gender ?? '') === 'Other'
      ? $request->gender_other
      : $request->gender;

    // JSON-encoded checkboxes
    $data['gad_challenges'] = json_encode($request->gad_challenges ?? []);
    $data['gad_trainings'] = json_encode($request->gad_trainings ?? []);
    $data['desired_mode'] = json_encode($request->intervention_modes ?? []);

    return $data;
  }

  /**
   * Questions for the GAD form
   */
  private function getQuestions()
  {
    return [
      'I have a clear understanding of basic concepts and principles covered in Gender Sensitivity Training (GST).',
      'I recognize gender stereotypes and biases in the workplace and can take steps to address them.',
      'I understand the significance of Sexual Orientation, Gender Identity, and Expression, and Sex Characteristics (SOGIESC) in promoting inclusivity.',
      'I know how to apply gender analysis to identify gaps in program design and delivery.',
      'I am aware of the key legal frameworks and mandates that support GAD implementation in the Philippines.',
      'I am familiar with the intersectionality of gender, age, ethnicity, disability, and socio-economic status in addressing GAD concerns.',
      'I am confident in aligning GAD strategic goals with organizational objectives.',
      'I am knowledgeable about the steps involved in formulating a GAD Agenda for long-term planning.',
      'I understand how to use a GAD Strategic Plan to ensure sustainability and measurable impacts.',
      'I am proficient in utilizing the Harmonized Gender and Development Guidelines (HGDG) for project assessment.',
      'I can confidently apply the HGDG Design Checklist to create gender-responsive programs and projects.',
      'I know how to use the HGDG Project Implementation, Management, Monitoring, and Evaluation (PIMME) Checklist to measure progress.',
      'I am able to integrate GAD principles into organizational monitoring and evaluation systems.',
      'I understand the requirements and procedures for preparing a GAD Plan and Budget (GPB).',
      'I have sufficient knowledge of crafting a comprehensive and accurate GAD Accomplishment Report (GAD AR).',
      'I can evaluate the outcomes of GAD interventions and provide actionable recommendations.',
      'I use gender-sensitive language when communicating with stakeholders in the workplace and community.',
      'I recognize and respect the cultural and social diversity of stakeholders when implementing programs and projects.',
      'I am aware of the role of GAD principles in fostering an equitable and inclusive workplace culture.',
      'I am familiar with organizational policies that promote gender equality and women’s empowerment.',
      'I believe that addressing GAD concerns contributes to organizational performance and public trust.',
      'I can identify workplace practices that unintentionally perpetuate gender inequality.',
      'I am knowledgeable about global trends and innovations in Gender and Development (e.g., gender-responsive budgeting, empowerment programs).',
      'I can facilitate discussions and workshops on GAD topics effectively.',
      'I know how to collaborate with external partners and stakeholders in advancing GAD initiatives.',
    ];
  }

  /**
   * GAD Challenges options
   */
  private function getGadChallenges()
  {
    return [
      "Lack of knowledge about GAD policies and principles among staff or stakeholders.",
      "Insufficient GAD-related training opportunities for employees or partners.",
      "Resistance from colleagues or stakeholders in adopting GAD policies and practices.",
      "Insufficient funding for GAD plans, programs, or activities.",
      "Difficulty balancing GAD implementation with existing workloads and priorities.",
      "Insufficient advocacy or prioritization from management or supervisors.",
      "Lack of access to GAD handbooks, guidelines, or materials for effective implementation.",
      "Challenges in integrating gender perspectives into project design, implementation, and evaluation.",
      "Local traditions or norms conflicting with gender equality principles.",
      "Limited capacity to monitor and evaluate GAD-related progress or outcomes effectively.",
      "Difficulty in effectively disseminating GAD information across diverse audiences.",
      "Difficulty in ensuring active participation and support from external partners or stakeholders.",
      "Challenges in collecting, analyzing, and using gender-specific data for planning and decision-making.",
      "Other"
    ];
  }

  /**
   * GAD Trainings options
   */
  private function getGadTrainings()
  {
    return [
      "Basic Gender Sensitivity Training (GST)",
      "Sexual Orientation, Gender Identity and Expression and Sex Characteristics (SOGIESC)",
      "Fundamentals of Gender Mainstreaming, GAD Mandates, and Gender Analysis",
      "Formulating A GAD Agenda - GAD Strategic Framework, and GAD Strategic Plan",
      "Harmonized Gender and Development Guidelines (HGDG) and HGDG Design Checklist",
      "HGDG Project Implementation, Management, Monitoring and Evaluation (PIMME) Checklist",
      "GAD Plan and Budget (GPB) and GAD Accomplishment Report (GAD AR)",
      "Language of GAD in the Workplace and in the Community",
      "Evidence Result Based Decision Making",
      "None",
      "Other"
    ];
  }

  /**
   * Desired mode of intervention options
   */
  private function getInterventionModes()
  {
    return [
      "Face-to-Face Training",
      "Virtual Learning",
      "Modular Learning",
      "Hybrid (Face-to-Face and Virtual)",
      "Other"
    ];
  }
}
