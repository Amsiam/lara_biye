<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>Profile - {{ $user->name }}</title>
    <style>
        body {
            font-family: kalpurush, sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Header */
        .header {
            background-color: #490b22;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            margin-bottom: 15px;
        }

        .header h1 {
            font-size: 22pt;
            margin: 0 0 5px 0;
            color: #ffffff;
        }

        .header .member-id {
            font-size: 11pt;
            color: #f0f0f0;
        }

        .header .verified {
            background-color: #22c55e;
            color: #ffffff;
            padding: 3px 12px;
            font-size: 9pt;
            display: inline-block;
            margin-top: 8px;
        }

        /* Profile Image */
        .profile-img-container {
            text-align: center;
            margin-bottom: 15px;
        }

        .profile-img {
            width: 100px;
            height: 100px;
            border: 3px solid #E33183;
        }

        /* Section */
        .section {
            margin-bottom: 12px;
            border: 1px solid #ddd;
        }

        .section-title {
            background-color: #490b22;
            color: #ffffff;
            padding: 8px 12px;
            font-size: 12pt;
            font-weight: bold;
            margin: 0;
        }

        .section-body {
            padding: 10px;
            background-color: #ffffff;
        }

        /* Bio */
        .bio-box {
            background-color: #fdf2f8;
            border-left: 4px solid #E33183;
            padding: 12px;
            font-style: italic;
            color: #555;
        }

        /* Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table td {
            padding: 6px 8px;
            vertical-align: top;
            border-bottom: 1px solid #f0f0f0;
        }

        .data-table .label {
            font-size: 9pt;
            color: #666;
            text-transform: uppercase;
            font-weight: bold;
            width: 25%;
        }

        .data-table .value {
            font-size: 10pt;
            color: #222;
            width: 25%;
        }

        /* Two Column Layout */
        .two-col {
            width: 100%;
        }

        .two-col td {
            width: 50%;
            vertical-align: top;
            padding: 0 5px;
        }

        /* Footer */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 9pt;
            color: #666;
        }

        .footer .brand {
            color: #E33183;
            font-weight: bold;
            font-size: 11pt;
        }

        /* Page break control */
        .no-break {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>

@php
    $imageData = null;
    if ($user->basicInfo?->image && $user->basicInfo->image !== 'default.png') {
        $imageFile = $user->basicInfo->image;
        $imagePath = null;

        // Image is stored as /storage/photos/filename.jpg
        // Real file is in storage/app/public/photos/filename.jpg
        if (str_starts_with($imageFile, '/storage/')) {
            $relativePath = str_replace('/storage/', '', $imageFile);
            $imagePath = storage_path('app/public/' . $relativePath);
        } else {
            $possiblePaths = [
                storage_path('app/public/photos/' . $imageFile),
                storage_path('app/public/' . $imageFile),
            ];
            foreach ($possiblePaths as $path) {
                if (file_exists($path)) {
                    $imagePath = $path;
                    break;
                }
            }
        }

        if ($imagePath && file_exists($imagePath)) {
            $imageContent = file_get_contents($imagePath);
            $imageType = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
            $mimeTypes = ['png' => 'image/png', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'gif' => 'image/gif'];
            $mimeType = $mimeTypes[$imageType] ?? 'image/jpeg';
            $imageData = 'data:' . $mimeType . ';base64,' . base64_encode($imageContent);
        }
    }
@endphp

<!-- Header with Profile Image -->
<div class="header">
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            @if($imageData)
            <td style="width: 110px; vertical-align: middle; text-align: center;">
                <img src="{{ $imageData }}" alt="Profile" style="width: 90px; height: 90px; border-radius: 50%; border: 3px solid #E33183;">
            </td>
            @endif
            <td style="vertical-align: middle; text-align: {{ $imageData ? 'left' : 'center' }}; padding-left: {{ $imageData ? '10px' : '0' }};">
                <div style="font-size: 22pt; font-weight: bold; color: #ffffff; margin-bottom: 5px;">{{ $user->name }}</div>
                <div style="font-size: 11pt; color: #f0f0f0; margin-bottom: 5px;">Member ID: {{ $user->id }}</div>
                @if($user->isProfileVerified())
                    <span style="background-color: #22c55e; color: #ffffff; padding: 3px 12px; font-size: 9pt; display: inline-block;">&#10003; Verified Profile</span>
                @endif
            </td>
        </tr>
    </table>
</div>

<!-- Introduction -->
@if($user->basicInfo?->bio)
<div class="section no-break">
    <div class="section-title">Introduction</div>
    <div class="section-body">
        <div class="bio-box">{{ $user->basicInfo->bio }}</div>
    </div>
</div>
@endif

<!-- Basic Information -->
<div class="section no-break">
    <div class="section-title">Basic Information</div>
    <div class="section-body">
        <table class="data-table">
            <tr>
                <td class="label">Date of Birth</td>
                <td class="value">{{ $user->basicInfo?->dob ? \Carbon\Carbon::parse($user->basicInfo->dob)->format('d M, Y') : '-' }}</td>
                <td class="label">Gender</td>
                <td class="value">{{ $user->basicInfo?->gender ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Marital Status</td>
                <td class="value">{{ $user->basicInfo?->marital_status ?? '-' }}</td>
                <td class="label">Religion</td>
                <td class="value">{{ $user->basicInfo?->religion ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Height</td>
                <td class="value">{{ $user->basicInfo?->height ?? '-' }}</td>
                <td class="label">Weight</td>
                <td class="value">{{ $user->basicInfo?->weight ? $user->basicInfo->weight . ' kg' : '-' }}</td>
            </tr>
            <tr>
                <td class="label">Blood Group</td>
                <td class="value">{{ $user->basicInfo?->blood_group ?? '-' }}</td>
                <td class="label">On Behalf</td>
                <td class="value">{{ $user->basicInfo?->on_behalf ?? '-' }}</td>
            </tr>
        </table>
    </div>
</div>

<!-- Present Address -->
@if($user->location && ($isOwner || $user->location->is_shown))
<div class="section no-break">
    <div class="section-title">Present Address</div>
    <div class="section-body">
        <table class="data-table">
            <tr>
                <td class="label">Country</td>
                <td class="value">{{ $user->location->country ?? '-' }}</td>
                <td class="label">Division</td>
                <td class="value">{{ $user->location->division ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">District</td>
                <td class="value">{{ $user->location->district ?? '-' }}</td>
                <td class="label">Upazilla</td>
                <td class="value">{{ $user->location->upazilla ?? '-' }}</td>
            </tr>
            @if($user->location->union)
            <tr>
                <td class="label">Union</td>
                <td class="value" colspan="3">{{ $user->location->union }}</td>
            </tr>
            @endif
        </table>
    </div>
</div>
@endif

<!-- Education & Career -->
@if($user->education && ($isOwner || $user->education->is_shown))
<div class="section no-break">
    <div class="section-title">Education & Career</div>
    <div class="section-body">
        <table class="data-table">
            <tr>
                <td class="label">Highest Education</td>
                <td class="value">{{ $user->education->highest_education ?? '-' }}</td>
                <td class="label">Employed In</td>
                <td class="value">{{ $user->education->employed_in ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Occupation</td>
                <td class="value">{{ $user->education->occupation ?? '-' }}</td>
                <td class="label">Annual Income</td>
                <td class="value">{{ $user->education->annual_income ? number_format($user->education->annual_income) . ' BDT' : '-' }}</td>
            </tr>
        </table>
    </div>
</div>
@endif

<!-- Physical Attributes -->
@if($user->physical_attr && ($isOwner || $user->physical_attr->is_shown))
<div class="section no-break">
    <div class="section-title">Physical Attributes</div>
    <div class="section-body">
        <table class="data-table">
            <tr>
                <td class="label">Eye Color</td>
                <td class="value">{{ $user->physical_attr->eye_color ?? '-' }}</td>
                <td class="label">Hair Color</td>
                <td class="value">{{ $user->physical_attr->hair_color ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Complexion</td>
                <td class="value">{{ $user->physical_attr->complexion ?? '-' }}</td>
                <td class="label">Body Type</td>
                <td class="value">{{ $user->physical_attr->body_type ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Body Art</td>
                <td class="value">{{ $user->physical_attr->body_art ?? '-' }}</td>
                <td class="label">Any Disability</td>
                <td class="value">{{ $user->physical_attr->any_disability ?? '-' }}</td>
            </tr>
        </table>
    </div>
</div>
@endif

<!-- Language -->
@if($user->language && ($isOwner || $user->language->is_shown))
<div class="section no-break">
    <div class="section-title">Language</div>
    <div class="section-body">
        <table class="data-table">
            <tr>
                <td class="label">Mother Tongue</td>
                <td class="value">{{ $user->language->mother_tongue ?? '-' }}</td>
                <td class="label">Other Languages</td>
                <td class="value">{{ $user->language->language ?? '-' }}</td>
            </tr>
        </table>
    </div>
</div>
@endif

<!-- Hobbies & Interests -->
@if($user->hobby && ($isOwner || $user->hobby->is_shown))
<div class="section no-break">
    <div class="section-title">Hobbies & Interests</div>
    <div class="section-body">
        <table class="data-table">
            <tr>
                <td class="label">Hobbies</td>
                <td class="value">{{ $user->hobby->hobbies ?? '-' }}</td>
                <td class="label">Interests</td>
                <td class="value">{{ $user->hobby->interests ?? '-' }}</td>
            </tr>
            @if($user->hobby->music ?? $user->hobby->books ?? null)
            <tr>
                <td class="label">Music</td>
                <td class="value">{{ $user->hobby->music ?? '-' }}</td>
                <td class="label">Books</td>
                <td class="value">{{ $user->hobby->books ?? '-' }}</td>
            </tr>
            @endif
            @if($user->hobby->movies ?? $user->hobby->sports ?? null)
            <tr>
                <td class="label">Movies</td>
                <td class="value">{{ $user->hobby->movies ?? '-' }}</td>
                <td class="label">Sports</td>
                <td class="value">{{ $user->hobby->sports ?? '-' }}</td>
            </tr>
            @endif
        </table>
    </div>
</div>
@endif

<!-- Personal Attitude -->
@if($user->personal && ($isOwner || $user->personal->is_shown))
<div class="section no-break">
    <div class="section-title">Personal Attitude & Behavior</div>
    <div class="section-body">
        <table class="data-table">
            <tr>
                <td class="label">Affection</td>
                <td class="value">{{ $user->personal->affection ?? '-' }}</td>
                <td class="label">Humor</td>
                <td class="value">{{ $user->personal->humor ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Political Views</td>
                <td class="value">{{ $user->personal->political_views ?? '-' }}</td>
                <td class="label">Religious Views</td>
                <td class="value">{{ $user->personal->religious_views ?? '-' }}</td>
            </tr>
        </table>
    </div>
</div>
@endif

<!-- Spiritual & Social -->
@if($user->spiritualSocial && ($isOwner || $user->spiritualSocial->is_shown))
<div class="section no-break">
    <div class="section-title">Spiritual & Social Background</div>
    <div class="section-body">
        <table class="data-table">
            <tr>
                <td class="label">Religion</td>
                <td class="value">{{ $user->spiritualSocial->religion ?? '-' }}</td>
                <td class="label">Caste</td>
                <td class="value">{{ $user->spiritualSocial->caste ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Sub Caste</td>
                <td class="value">{{ $user->spiritualSocial->sub_caste ?? '-' }}</td>
                <td class="label">Ethnicity</td>
                <td class="value">{{ $user->spiritualSocial->ethnicity ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Personal Value</td>
                <td class="value">{{ $user->spiritualSocial->personal_value ?? '-' }}</td>
                <td class="label">Family Value</td>
                <td class="value">{{ $user->spiritualSocial->family_value ?? '-' }}</td>
            </tr>
        </table>
    </div>
</div>
@endif

<!-- Lifestyle -->
@if($user->lifestyle && ($isOwner || $user->lifestyle->is_shown))
<div class="section no-break">
    <div class="section-title">Lifestyle</div>
    <div class="section-body">
        <table class="data-table">
            <tr>
                <td class="label">Diet</td>
                <td class="value">{{ $user->lifestyle->diet ?? '-' }}</td>
                <td class="label">Drink</td>
                <td class="value">{{ $user->lifestyle->drink ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Smoke</td>
                <td class="value">{{ $user->lifestyle->smoke ?? '-' }}</td>
                <td class="label">Living With</td>
                <td class="value">{{ $user->lifestyle->living_with ?? '-' }}</td>
            </tr>
        </table>
    </div>
</div>
@endif

<!-- Permanent Address -->
@if($user->parmanent && ($isOwner || $user->parmanent->is_shown))
<div class="section no-break">
    <div class="section-title">Permanent Address</div>
    <div class="section-body">
        <table class="data-table">
            <tr>
                <td class="label">Country</td>
                <td class="value">{{ $user->parmanent->country ?? '-' }}</td>
                <td class="label">Division</td>
                <td class="value">{{ $user->parmanent->division ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">District</td>
                <td class="value">{{ $user->parmanent->district ?? '-' }}</td>
                <td class="label">Upazilla</td>
                <td class="value">{{ $user->parmanent->upazilla ?? '-' }}</td>
            </tr>
        </table>
    </div>
</div>
@endif

<!-- Family Information -->
@if($user->family && ($isOwner || $user->family->is_shown))
<div class="section no-break">
    <div class="section-title">Family Information</div>
    <div class="section-body">
        <table class="data-table">
            <tr>
                <td class="label">Father's Name</td>
                <td class="value">{{ $user->family->father ?? '-' }}</td>
                <td class="label">Father's Occupation</td>
                <td class="value">{{ $user->family->father_occupation ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Mother's Name</td>
                <td class="value">{{ $user->family->mother ?? '-' }}</td>
                <td class="label">Mother's Occupation</td>
                <td class="value">{{ $user->family->mother_occupation ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Brothers</td>
                <td class="value">{{ $user->family->brother ?? '-' }}</td>
                <td class="label">Sisters</td>
                <td class="value">{{ $user->family->sister ?? '-' }}</td>
            </tr>
        </table>

        <!-- Sibling Information -->
        @if($user->siblingInfo && $user->siblingInfo->count() > 0)
        <div style="margin-top: 15px; border-top: 1px solid #eee; padding-top: 10px;">
            <div style="font-size: 10pt; font-weight: bold; color: #490b22; margin-bottom: 8px;">Sibling Details</div>
            <table class="data-table" style="border: 1px solid #ddd;">
                <tr style="background-color: #f5f5f5;">
                    <td class="label" style="border-bottom: 1px solid #ddd; font-weight: bold;">Occupation</td>
                    <td class="label" style="border-bottom: 1px solid #ddd; font-weight: bold;">Academic Background</td>
                </tr>
                @foreach($user->siblingInfo as $sibling)
                <tr>
                    <td class="value">{{ $sibling->occupation ?? '-' }}</td>
                    <td class="value">{{ $sibling->academic_background ?? '-' }}</td>
                </tr>
                @endforeach
            </table>
        </div>
        @endif
    </div>
</div>
@endif

<!-- Partner Expectations -->
@if($user->partnerExpectation)
<div class="section no-break">
    <div class="section-title">Partner Expectations</div>
    <div class="section-body">
        @if($user->partnerExpectation->general_requirement)
        <div style="background-color: #f9fafb; padding: 10px; margin-bottom: 10px; border-left: 3px solid #E33183;">
            <strong style="font-size: 9pt; color: #666; text-transform: uppercase;">General Requirements</strong><br>
            <span style="color: #333;">{{ $user->partnerExpectation->general_requirement }}</span>
        </div>
        @endif
        <table class="data-table">
            <tr>
                <td class="label">Age</td>
                <td class="value">{{ $user->partnerExpectation->age ?? '-' }}</td>
                <td class="label">Height</td>
                <td class="value">
                    @if($user->partnerExpectation->height_from || $user->partnerExpectation->height_to)
                        {{ $user->partnerExpectation->height_from ?? '' }} - {{ $user->partnerExpectation->height_to ?? '' }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Weight</td>
                <td class="value">
                    @if($user->partnerExpectation->weight_from || $user->partnerExpectation->weight_to)
                        {{ $user->partnerExpectation->weight_from ?? '' }} - {{ $user->partnerExpectation->weight_to ?? '' }} kg
                    @else
                        -
                    @endif
                </td>
                <td class="label">Marital Status</td>
                <td class="value">{{ $user->partnerExpectation->marital_status ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Children Acceptable</td>
                <td class="value">{{ $user->partnerExpectation->with_children_acceptables ?? '-' }}</td>
                <td class="label">Religion</td>
                <td class="value">{{ $user->partnerExpectation->religion ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Caste/Sect</td>
                <td class="value">{{ $user->partnerExpectation->caste_sect ?? '-' }}</td>
                <td class="label">Sub Caste</td>
                <td class="value">{{ $user->partnerExpectation->sub_caste ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Education</td>
                <td class="value">{{ $user->partnerExpectation->education ?? '-' }}</td>
                <td class="label">Profession</td>
                <td class="value">{{ $user->partnerExpectation->profession ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Drinking Habits</td>
                <td class="value">{{ $user->partnerExpectation->drinking_habits ?? '-' }}</td>
                <td class="label">Smoking Habits</td>
                <td class="value">{{ $user->partnerExpectation->smoking_habits ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Diet</td>
                <td class="value">{{ $user->partnerExpectation->diet ?? '-' }}</td>
                <td class="label">Body Type</td>
                <td class="value">{{ $user->partnerExpectation->body_type ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Complexion</td>
                <td class="value">{{ $user->partnerExpectation->complexion ?? '-' }}</td>
                <td class="label">Any Disability</td>
                <td class="value">{{ $user->partnerExpectation->any_disability ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Mother Tongue</td>
                <td class="value">{{ $user->partnerExpectation->mother_tongue ?? '-' }}</td>
                <td class="label">Manglik</td>
                <td class="value">{{ $user->partnerExpectation->manglik ? 'Yes' : 'No' }}</td>
            </tr>
            <tr>
                <td class="label">Personal Value</td>
                <td class="value">{{ $user->partnerExpectation->personal_value ?? '-' }}</td>
                <td class="label">Family Value</td>
                <td class="value">{{ $user->partnerExpectation->family_value ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Country of Residence</td>
                <td class="value">{{ $user->partnerExpectation->country_of_residence ?? '-' }}</td>
                <td class="label">Preferred Country</td>
                <td class="value">{{ $user->partnerExpectation->prefered_country ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Preferred State</td>
                <td class="value">{{ $user->partnerExpectation->prefered_state ?? '-' }}</td>
                <td class="label">Preferred Status</td>
                <td class="value">{{ $user->partnerExpectation->prefered_status ?? '-' }}</td>
            </tr>
        </table>
    </div>
</div>
@endif

</body>
</html>
