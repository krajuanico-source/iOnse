@extends('layouts/contentNavbarLayout')

@section('title', 'Character Building Program')

@section('content')

   <!-- Toastr CSS -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>



    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 font-bold">Character Building Program</h1>
        <div class="flex items-center space-x-6">
                <a href="#" class="hover:text-blue-200" onclick="showTab('overview')">Overview</a>
                <a href="#" class="hover:text-blue-200" onclick="showTab('nominate')">Nominate</a>
                <a href="#" class="hover:text-blue-200" onclick="showTab('manage')">Manage</a>
                <div class="flex items-center space-x-2">
                </div>
            </div>
    </div>


    <style>
        .nomination-writeup {
            min-height: 120px;
        }
        .character-card {
            transition: all 0.3s ease;
        }
        .character-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-2">
            </div>
        </div>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <!-- Overview Tab -->
        <div id="overview-tab" class="tab-content active">
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-blue-50 p-6 rounded-lg character-card">
                        <div class="flex items-center mb-4">
                            <div class="bg-blue-100 p-3 rounded-full mr-4">
                                <i class="fas fa-trophy text-blue-600 text-xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold">Program Purpose</h3>
                        </div>
                        <p class="text-gray-700">Recognize employees who exemplify outstanding character qualities that align with our organization's values and mission.</p>
                    </div>
                    
                    <div class="bg-green-50 p-6 rounded-lg character-card">
                        <div class="flex items-center mb-4">
                            <div class="bg-green-100 p-3 rounded-full mr-4">
                                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold">Eligibility</h3>
                        </div>
                        <ul class="list-disc list-inside text-gray-700 space-y-1">
                            <li>All regular employees</li>
                            <li>Minimum 1 year of service</li>
                            <li>No disciplinary actions in past year</li>
                        </ul>
                    </div>
                    
                    <div class="bg-purple-50 p-6 rounded-lg character-card">
                        <div class="flex items-center mb-4">
                            <div class="bg-purple-100 p-3 rounded-full mr-4">
                                <i class="fas fa-calendar-alt text-purple-600 text-xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold">Nomination Timeline</h3>
                        </div>
                        <p class="text-gray-700">Nominations open on the 1st of each month and close on the 15th. Winners are announced on the last business day of the month.</p>
                    </div>
                </div>
                
                <h3 class="text-xl font-semibold text-blue-800 mb-3">Current Month's Character Quality</h3>
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-lightbulb text-yellow-500 text-2xl mt-1 mr-3"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800">Innovation</h4>
                            <p class="text-gray-700 mt-1">Recognizing employees who demonstrate creative problem-solving, original thinking, and implementation of new ideas that improve our services or operations.</p>
                        </div>
                    </div>
                </div>
                
                <h3 class="text-xl font-semibold text-blue-800 mb-3">Previous Winners</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="bg-white border rounded-lg p-4 shadow-sm">
                        <div class="flex items-center mb-3">
                            <img src="https://placehold.co/60x60" alt="Previous winner Sarah Johnson smiling in professional attire" class="rounded-full mr-3">
                            <div>
                                <h4 class="font-semibold">Sarah Johnson</h4>
                                <p class="text-sm text-gray-600">Finance Department</p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-700">"For developing an innovative budgeting tool that saved the department 120 hours monthly."</p>
                    </div>
                    <div class="bg-white border rounded-lg p-4 shadow-sm">
                        <div class="flex items-center mb-3">
                            <img src="https://placehold.co/60x60" alt="Previous winner Michael Chen in a professional headshot" class="rounded-full mr-3">
                            <div>
                                <h4 class="font-semibold">Michael Chen</h4>
                                <p class="text-sm text-gray-600">IT Services</p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-700">"For creating an automated system that reduced data entry errors by 80%."</p>
                    </div>
                    <div class="bg-white border rounded-lg p-4 shadow-sm">
                        <div class="flex items-center mb-3">
                            <img src="https://placehold.co/60x60" alt="Previous winner Maria Rodriguez smiling in professional attire" class="rounded-full mr-3">
                            <div>
                                <h4 class="font-semibold">Maria Rodriguez</h4>
                                <p class="text-sm text-gray-600">Client Services</p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-700">"For redesigning our client intake form that improved client satisfaction scores by 25%."</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nomination Tab -->
        <div id="nominate-tab" class="tab-content">
            <div class="max-w-3xl mx-auto">
                <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                    <h2 class="text-2xl font-bold text-blue-800 mb-6">Nomination Form</h2>
                    
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                            </div>
                            <div>
                                <p class="text-sm text-blue-800">
                                    You are nominating for the <span class="font-semibold">Innovation</span> character quality this month. 
                                    Nominations close on <span class="font-semibold">March 15, 2024</span>.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <form id="nominationForm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Left Column -->
                            <div>
                                <div class="mb-4">
                                    <label for="nomineeName" class="block text-gray-700 font-medium mb-2">Nominee's Full Name</label>
                                    <input type="text" id="nomineeName" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" required>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="position" class="block text-gray-700 font-medium mb-2">Position</label>
                                    <input type="text" id="position" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" required>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="division" class="block text-gray-700 font-medium mb-2">Division/Section/Unit</label>
                                    <select id="division" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" required>
                                        <option value="">Select Division</option>
                                        <option value="finance">Finance</option>
                                        <option value="hr">Human Resources</option>
                                        <option value="it">Information Technology</option>
                                        <option value="operations">Operations</option>
                                        <option value="programs">Programs</option>
                                    </select>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="yearsOfService" class="block text-gray-700 font-medium mb-2">Years of Service</label>
                                    <input type="number" id="yearsOfService" min="1" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" required>
                                </div>
                            </div>
                            
                            <!-- Right Column -->
                            <div>
                                <div class="mb-4">
                                    <label for="characterQuality" class="block text-gray-700 font-medium mb-2">Character Quality</label>
                                    <select id="characterQuality" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" required>
                                        <option value="innovation">Innovation</option>
                                        <option value="integrity">Integrity</option>
                                        <option value="teamwork">Teamwork</option>
                                        <option value="service">Service</option>
                                    </select>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="nominationWriteup" class="block text-gray-700 font-medium mb-2">Nomination Write-up (max 500 characters)</label>
                                    <textarea id="nominationWriteup" maxlength="500" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition nomination-writeup" required></textarea>
                                    <div class="text-right text-sm text-gray-500 mt-1">
                                        <span id="charCount">0</span>/500 characters
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="supportingDoc" class="block text-gray-700 font-medium mb-2">Supporting Document (Optional)</label>
                                    <div class="flex items-center justify-center w-full">
                                        <label for="supportingDoc" class="flex flex-col w-full border-2 border-dashed rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                            <div class="flex flex-col items-center justify-center py-6 px-4">
                                                <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-2"></i>
                                                <p class="text-sm text-gray-500 text-center">
                                                    <span class="font-semibold text-blue-600">Click to upload</span> or drag and drop<br>
                                                    PDF only (Max 5MB)
                                                </p>
                                            </div>
                                            <input id="supportingDoc" type="file" class="hidden" accept=".pdf">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                                Save Draft
                            </button>
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Submit Nomination
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Current Nominations -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold text-blue-800 mb-4">Your Nominations</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nominee</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quality</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img src="https://placehold.co/40x40" alt="Nominee Robert Smith in professional headshot" class="rounded-full">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">Robert Smith</div>
                                                <div class="text-sm text-gray-500">Finance</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Innovation
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Pending Review
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                        <a href="#" class="text-red-600 hover:text-red-900">Withdraw</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img src="https://placehold.co/40x40" alt="Nominee Lisa Wong in professional headshot" class="rounded-full">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">Lisa Wong</div>
                                                <div class="text-sm text-gray-500">IT</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                            Teamwork
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Closed - Not Selected
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <a href="#" class="text-blue-600 hover:text-blue-900">View</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Management Tab (Admin Only) -->
        <div id="manage-tab" class="tab-content">
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-2xl font-bold text-blue-800 mbare-6">Character Quality Management</h2>
                
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-blue-800 mb-4">Current Month Setup</h3>
                    
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <form>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="currentQuality" class="block text-gray-700 font-medium mb-2">Character Quality for Current Month</label>
                                    <select id="currentQuality" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                                        <option value="innovation">Innovation</option>
                                        <option value="integrity">Integrity</option>
                                        <option value="teamwork">Teamwork</option>
                                        <option value="service">Service</option>
                                        <option value="excellence">Excellence</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="nominationDeadline" class="block text-gray-700 font-medium mb-2">Nomination Deadline</label>
                                    <input type="date" id="nominationDeadline" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                                </div>
                                
                                <div>
                                    <label for="awardOverview" class="block text-gray-700 font-medium mb-2">Award Overview</label>
                                    <textarea id="awardOverview" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" rows="3"></textarea>
                                </div>
                                
                                <div>
                                    <label for="eligibilityCriteria" class="block text-gray-700 font-medium mb-2">Eligibility Criteria</label>
                                    <textarea id="eligibilityCriteria" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" rows="3"></textarea>
                                </div>
                            </div>
                            
                            <div class="mt-6">
                                <label for="memorandumLink" class="block text-gray-700 font-medium mb-2">Memorandum Order Link</label>
                                <input type="url" id="memorandumLink" placeholder="https://" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                            </div>
                            
                            <div class="mt-6 flex justify-between items-center">
                                <div>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-offset-0 focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-gray-700">Email Notification</span>
                                    </label>
                                    <p class="text-sm text-gray-500 mt-1">Send email to all employees when opening nominations</p>
                                </div>
                                <button type="button" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold text-blueC-800">Manage Character Qualities</h3>
                        <button class="px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition flex items-center">
                            <i class="fas fa-plus mr-2"></i> Add New
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="bg-white border rounded-lg p-4 shadow-sm">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-semibold text-lg">Innovation</h4>
                                <div class="flex space-x-2">
                                    <button class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600">Demonstrating creative problem-solving and implementing new ideas</p>
                        </div>
                        <div class="bg-white border rounded-lg p-4 shadow-sm">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-semibold text-lg">Integrity</h4>
                                <div class="flex space-x-2">
                                    <button class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600">Acting with honesty and strong moral principles</p>
                        </div>
                        <div class="bg-white border rounded-lg p-4 shadow-sm">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-semibold text-lg">Teamwork</h4>
                                <div class="flex space-x-2">
                                    <button class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600">Collaborating effectively to achieve common goals</p>
                        </div>
                        <div class="bg-white border rounded-lg p-4 shadow-sm">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-semibold text-lg">Service</h4>
                                <div class="flex space-x-2">
                                    <button class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600">Demonstrating commitment to helping others</p>
                        </div>
                        <div class="bg-white border rounded-lg p-4 shadow-sm">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-semibold text-lg">Excellence</h4>
                                <div class="flex space-x-2">
                                    <button class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600">Consistently exceeding expectations in performance</p>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-xl font-semibold text-blue-800 mb-4">Nomination Reports</h3>
                    
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <div class="flex flex-wrap gap-4 mb-4">
                            <div class="flex-1 min-w-[200px]">
                                <label for="reportMonth" class="block text-gray-700 font-medium mb-2">Month</label>
                                <select id="reportMonth" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                                    <option value="">All Months</option>
                                    <option value="january">January</option>
                                    <option value="february">February</option>
                                    <option value="march">March</option>
                                </select>
                            </div>
                            <div class="flex-1 min-w-[200px]">
                                <label for="reportQuality" class="block text-gray-700 font-medium mb-2">Character Quality</label>
                                <select id="reportQuality" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                                    <option value="">All Qualities</option>
                                    <option value="innovation">Innovation</option>
                                    <option value="integrity">Integrity</option>
                                </select>
                            </div>
                            <div class="flex-1 min-w-[200px]">
                                <label for="reportStatus" class="block text-gray-700 font-medium mb-2">Status</label>
                                <select id="reportStatus" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                                    <option value="">All Statuses</option>
                                    <option value="open">Open</option>
                                    <option value="closed">Closed</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mt-4 flex justify-between">
                            <button class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                                <i class="fas fa-download mr-2"></i> Export to Excel
                            </button>
                            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition">
                                <i class="fas fa-filter mr-2"></i> Apply Filters
                            </button>
                        </div>
                    </div>
                    
                    <div class="mt-6 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nominee</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nominator</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quality</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img src="https://placehold.co/40x40" alt="Nominee David Miller in professional headshot" class="rounded-full">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">David Miller</div>
                                                <div class="text-sm text-gray-500">Finance Analyst</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">Sarah Johnson</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Innovation
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        Mar 5, 2024
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibound rounded-full bg-blue-100 text-blue-800">
                                            Under Review
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                        <a href="#" class="text-green-600 hover:text-green-900">Approve</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img src="https://placehold.co/40x40" alt="Nominee Emily Davis in professional headshot" class="rounded-full">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">Emily Davis</div>
                                                <div class="text-sm text-gray-500">HR Specialist</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">Michael Brown</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                            Teamwork
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        Feb 28, 2024
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Awarded
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="#" class="text-blue-600 hover:text-blue-900">View</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Tab switching functionality
        function showTab(tabName) {
            // Hide all tab content
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Show the selected tab
            document.getElementById(`${tabName}-tab`).classList.add('active');
            
            // Update active state in navigation (optional)
        }
        
        // Character counter for nomination write-up
        document.getElementById('nominationWriteup').addEventListener('input', function() {
            const charCount = this.value.length;
            document.getElementById('charCount').textContent = charCount;
            
            if (charCount > 500) {
                this.value = this.value.substring(0, 500);
                document.getElementById('charCount').textContent = 500;
            }
        });
        
        // Form submission handling
        document.getElementById('nominationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Here you would normally send the form data to the server
            // For demo purposes, we'll just show an alert
            alert('Nomination submitted successfully!');
            this.reset();
            document.getElementById('charCount').textContent = '0';
        });
    </script>




@endsection