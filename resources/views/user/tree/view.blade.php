@extends('user.layouts.app')
@section('content')
<?php
use App\Models\User;
use Illuminate\Support\Facades\Auth;
$user = Auth::user();


?>

{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}

<style>
    /* :root {
        --primary-color: #007bff;
        --secondary-color: #ff4d4d;
        --accent-color: #00c853;
        --bg-color: #f8f9fa;
        --text-color: #ffffff;
        --line-color: #ced4da;
        --shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    } */
    .tree-container {
        max-width: 100%;
        margin: 0 auto;
        padding: 20px 10px;
        background: #323434;
        border-radius: 12px;
        box-shadow: var(--shadow);
        overflow: hidden;
        position: relative;
    }
    .page-title {
        text-align: center;
        color: var(--text-color);
        margin-bottom: 8px;
        font-weight: 700;
        font-size: 2rem;
        background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: gradientText 4s ease infinite;
        background-size: 200% 200%;
    }
    @keyframes gradientText {
        0% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
        100% {
            background-position: 0% 50%;
        }
    }
    .page-subtitle {
        color: #6c757d;
        font-size: 0.95rem;
        margin-bottom: 15px;
        text-align: center;
        letter-spacing: 0.3px;
        animation: fadeIn 1s ease-out;
    }
    .controls {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin: 12px 0 10px;
        flex-wrap: wrap;
        z-index: 10;
    }
    .btn {
        padding: 8px 16px;
        color: white;
        border: none;
        border-radius: 20px;
        cursor: pointer;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: var(--transition);
        box-shadow: var(--shadow);
        position: relative;
        overflow: hidden;
        z-index: 1;
        font-size: 0.85rem;
    }
    .btn::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.25);
        transform: scaleX(0);
        transform-origin: right;
        transition: transform 0.3s ease;
        z-index: -1;
    }
    .btn:hover::after,
    .btn:active::after {
        transform: scaleX(1);
        transform-origin: left;
    }
    .btn:hover,
    .btn:active {
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    }
    .btn-expand {
        background: var(--accent-color);
    }
    .btn-collapse {
        background: var(--secondary-color);
    }
    .btn-zoom-in {
        background: var(--primary-color);
    }
    .btn-zoom-out {
        background: #6c757d;
    }
    .btn-copy,
    .genealogy-tree .btn.btn-register {
        background: #6c757d;
        width: 112px;
        min-width: 112px;
        max-width: 112px;
        justify-content: center;
        margin: 6px auto 0;
        padding: 4px 10px;
        font-size: 12px;
        line-height: 1.2;
        white-space: nowrap;
        overflow: visible;
        flex-shrink: 0;
        box-sizing: border-box;
    }
    .empty-node-buttons {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }
    .genealogy-scroll {
        display: block;
        box-sizing: border-box;
        overflow: auto;
        /* Room on every side so the sponsor and both legs stay off the edges. */
        padding: 32px 28px 56px;
        height: calc(100vh - 230px);
        min-height: 360px;
        text-align: left;
        scroll-behavior: auto;
        position: relative;
        cursor: grab;
        touch-action: none;
        user-select: none;
    }
    .genealogy-scroll.is-grabbing {
        cursor: grabbing;
    }
    .genealogy-scroll::-webkit-scrollbar {
        width: 5px;
        height: 5px;
    }
    .genealogy-scroll::-webkit-scrollbar-track {
        background: #e9ecef;
        border-radius: 8px;
    }
    .genealogy-scroll::-webkit-scrollbar-thumb {
        background: var(--primary-color);
        border-radius: 8px;
    }
    .genealogy-scroll::-webkit-scrollbar-thumb:hover {
        background: var(--accent-color);
    }
    .genealogy-tree {
        display: block;
        width: max-content;
        min-width: max-content;
        margin: 0 auto;
        padding: 8px 36px 28px;
        box-sizing: content-box;
        vertical-align: top;
        text-align: center;
        transform-origin: top center;
        transition: transform 0.4s ease;
        will-change: transform;
    }
    .genealogy-tree ul {
        padding-top: 12px;
        position: relative;
        padding-left: 0;
        padding-right: 0;
        margin: 0 auto;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        width: max-content;
        min-width: 0;
        gap: 0;
        transition: var(--transition);
    }
    .genealogy-tree > ul::before,
    .genealogy-tree ul ul::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        border-left: 2px solid var(--line-color);
        width: 0;
        height: 12px;
    }
    /* Compact columns: size to content only — do not stretch both legs
       to the widest subtree (that made horizontal lines screen-wide). */
    .genealogy-tree li {
        text-align: center;
        list-style-type: none;
        position: relative;
        padding: 12px 8px 0;
        transition: var(--transition);
        flex: 0 0 auto;
        width: auto;
        max-width: none;
        min-width: 0;
        box-sizing: border-box;
    }
    .genealogy-tree li::before,
    .genealogy-tree li::after {
        content: '';
        position: absolute;
        top: 0;
        right: 50%;
        border-top: 2px solid var(--line-color);
        width: 50%;
        height: 12px;
        transition: var(--transition);
    }
    .genealogy-tree li::after {
        right: auto;
        left: 50%;
        border-left: 2px solid var(--line-color);
    }
    .genealogy-tree li:only-child::after,
    .genealogy-tree li:only-child::before {
        display: none;
    }
    .genealogy-tree li:only-child {
        padding-top: 0;
    }
    .genealogy-tree li:first-child::before,
    .genealogy-tree li:last-child::after {
        border: 0 none;
    }
    .genealogy-tree li:last-child::before {
        border-right: 2px solid var(--line-color);
        border-radius: 0 5px 0 0;
    }
    .genealogy-tree li:first-child::after {
        border-radius: 5px 0 0 0;
    }
    .genealogy-tree > a,
    .genealogy-tree li a {
        text-decoration: none;
        color: var(--text-color);
        display: block;
        width: fit-content;
        margin-left: auto;
        margin-right: auto;
        border-radius: 8px;
        transition: var(--transition);
    }
    .genealogy-tree li a:hover,
    .genealogy-tree li a:active {
        transform: scale(1.02);
    }
    .genealogy-tree li a:hover+ul li::after,
    .genealogy-tree li a:hover+ul li::before,
    .genealogy-tree li a:hover+ul::before,
    .genealogy-tree li a:hover+ul ul::before {
        border-color: var(--primary-color);
    }
    .member-view-box {
        text-align: center;
        border-radius: 10px;
        position: relative;
        /* background: white; */
        transition: var(--transition);
        /* box-shadow: var(--shadow); */
        margin: 0 auto;
    }
    .btn i {
        font-size: 12px;
    }
    .member-view-box:hover,
    .member-view-box:active {
        transform: translateY(-4px);
        /* box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1); */
        /* border-color: var(--primary-color); */
    }
    .member-image {
        /*padding: 6px;*/
        width: 56px;
        margin: 0 auto;
        position: relative;
    }
    .member-image-inactive {
        /*padding: 6px;*/
        width: 56px;
        margin: 0 auto;
        position: relative;
    }
    .member-image img {
        width: 50px;
        height: 50px;
        /* border-radius: 50%; */
        object-fit: cover;
        transition: var(--transition);
        /* border: 2px solid var(--accent-color); */
    }
    .member-image-inactive img {
        width: 50px;
        height: 50px;
        /* border-radius: 50%; */
        object-fit: cover;
        transition: var(--transition);
        /* border: 2px solid #dc3545; */
    }
    .member-image img:hover,
    .member-image img:active {
        transform: scale(1.04);
    }
    .member-image-inactive img:hover,
    .member-image-inactive img:active {
        transform: scale(1.04);
    }
    .member-header {
        padding: 6px 0;
        text-align: center;
        background: linear-gradient(135deg, #004e05, var(--accent-color));
        color: white;
        font-size: 12px;
        font-weight: 600;
        border-radius: 8px 8px 0 0;
    }
    .member-header-inactive {
        padding: 6px 0;
        text-align: center;
        background: linear-gradient(135deg, #dc3545, #5a1a1a);
        color: white;
        font-size: 12px;
        font-weight: 600;
        border-radius: 8px 8px 0 0;
    }
    .member-footer {
        /* padding: 6px 4px; */
        text-align: center;
    }
    .member-footer div.name {
        color: #ffffff;
        font-size: 12px;
        margin-bottom: 4px;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .member-footer div.downline {
        color: #495057;
        font-size: 10px;
        font-weight: 500;
        display: flex;
        justify-content: center;
        gap: 6px;
    }
    .downline span {
        background: #e9ecef;
        padding: 2px 5px;
        border-radius: 12px;
        font-size: 9px;
    }
    .empty-node {
        background: #f1f3f5;
        color: #adb5bd;
        border: 2px dashed #ced4da;
        min-height: 140px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 140px;
        border-radius: 8px;
        transition: var(--transition);
        font-style: italic;
        font-size: 12px;
        margin: 0 auto;
    }
    .empty-node:hover,
    .empty-node:active {
        border-color: var(--secondary-color);
        color: var(--secondary-color);
    }
    .notification {
        position: fixed;
        top: 15px;
        right: 15px;
        padding: 10px 15px;
        background: var(--accent-color);
        color: white;
        border-radius: 6px;
        box-shadow: var(--shadow);
        transform: translateX(110%);
        opacity: 0;
        transition: var(--transition);
        z-index: 2000;
        font-weight: 500;
        font-size: 0.85rem;
    }
    .notification.show {
        transform: translateX(0);
        opacity: 1;
    }
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .status-active span {
        background: #00cb0ca6;
        padding: 2px 5px;
        border-radius: 12px;
        font-size: 12px;
        color: var(--text-color);
    }
    .status-inactive span {
        background: #dc3545cf;
        padding: 2px 5px;
        border-radius: 12px;
        font-size: 12px;
        color: var(--text-color);
    }
    @media (max-width: 992px) {
        .tree-container {
            padding: 15px 8px;
        }
        .page-title {
            font-size: 1.8rem;
        }
        .page-subtitle {
            font-size: 0.9rem;
        }
        .genealogy-scroll {
            height: calc(100vh - 180px);
            padding: 20px 12px 40px;
        }
    }
    @media (max-width: 576px) {
        .page-title {
            font-size: 1.6rem;
        }
        .page-subtitle {
            font-size: 0.85rem;
        }
        .controls {
            flex-direction: column;
            align-items: center;
        }
        .controls .btn {
            width: 100%;
            max-width: 180px;
            justify-content: center;
        }
        .genealogy-scroll {
            height: calc(100vh - 140px);
            min-height: 320px;
            padding: 16px 8px 36px;
        }
    }
</style>
<style>
    :root {
        --primary-color: #007bff;
        --secondary-color: #ff4d4d;
        --accent-color: #00c853;
        --bg-color: #f8f9fa;
        --text-color: #1f2a44;
        --line-color: #e5e7eb;
        --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .team-comparison-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }
    .team-title {
        background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: gradientText 4s ease infinite;
        background-size: 200% 200%;
        font-weight: 800;
        font-size: 2.5rem;
        text-align: center;
    }
    @keyframes gradientText {
        0% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
        100% {
            background-position: 0% 50%;
        }
    }
    .team-card {
        background: #323434;
        border-radius: 1rem;
        box-shadow: var(--shadow);
        transition: var(--transition);
        overflow: hidden;
    }
    .team-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }
    .team-header {
        padding: 1.5rem;
        font-weight: 700;
        font-size: 1.25rem;
        color: white;
    }
    .team-header-left {
        background: var(--primary-gradient);
    }
    .team-header-right {
        background: var(--primary-gradient);
    }
    .table-container {
        overflow-x: auto;
        padding: 1rem;
    }
    .team-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    .team-table th {
        background: #f9fafb24;
        color: #ffffff;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 1rem;
        text-align: left;
    }
    .team-table td {
        padding: 1rem;
        color: #ffffff;
        font-size: 0.875rem;
        font-weight: 500;
        border: 1px solid #d7d7d726 !important;
    }
    .team-table tr:hover {
        background: #f1f5f900;
    }
    @media (max-width: 768px) {
        .team-title {
            font-size: 2rem;
        }
        .team-header {
            font-size: 1.125rem;
        }
        .team-table th,
        .team-table td {
            font-size: 0.75rem;
            padding: 0.75rem;
        }
    }
    @media (max-width: 576px) {
        .team-comparison-container {
            padding: 1rem 0.5rem;
        }
        .team-title {
            font-size: 1.75rem;
        }
        .team-card {
            margin-bottom: 1rem;
        }
    }
</style>


<style>
    .tree-container{
    position: relative;
}

.tree-legend{
    position: absolute;
    top: 15px;
    right: 15px;
    background: rgba(255,255,255,0.95);
    padding: 10px 15px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.15);
    z-index: 999;
    min-width: 220px;
    text-align: left;
}

.tree-legend h6{
    margin: 0 0 8px;
    font-weight: 700;
    color: #000;
}

.legend-item{
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 5px;
    font-size: 13px;
    color: #000;
}

.legend-dot{
    width: 14px;
    height: 14px;
    border-radius: 50%;
    display: inline-block;
}

.legend-dot.green{
    background: #28a745;
}

.legend-dot.yellow{
    background: #ffc107;
}

.legend-dot.blue{
    background: #0d6efd;
}

.legend-dot.red{
    background: #dc3545;
}

@media(max-width:768px){
    .tree-legend{
        position: static;
        margin-bottom: 15px;
        width: fit-content;
    }
}
</style>


<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<div class="team-comparison-container">
    <h1 class="team-title mb-10">Team Comparison</h1>
    <div class="row">
        <div class="col-md-6">
            <!-- Left Team -->
            <div class="team-card">
                <h2 class="team-header team-header-left">Left Team</h2>
                <div class="table-container">
                    <table class="team-table">
                        <thead class="text-center">
                            <tr class="text-center">
                                <th>MEMBER</th>
                                <th>BV </th>
                                <th>GREEN UNIT</th>
                                <th>GREEN DIRECT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center">
                                <td>{{ $leftTeam['member'] ?? 39 }}</td>
                                <td>{{ $leftTeam['point'] ?? 96000 }}</td>
                                <td>{{ $leftTeam['green_unit'] ?? 38 }}</td>
                                <td>{{ $leftTeam['green_direct'] ?? 1 }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <!-- Right Team -->
            <div class="team-card">
                <h2 class="team-header team-header-right">Right Team</h2>
                <div class="table-container">
                    <table class="team-table">
                        <thead class="text-center">
                            <tr class="text-center">
                                <th>MEMBER</th>
                                <th>BV</th>
                                <th>GREEN UNIT</th>
                                <th>GREEN DIRECT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center">
                                <td>{{ $rightTeam['member'] ?? 16 }}</td>
                                <td>{{ $rightTeam['point'] ?? 40000 }}</td>
                                <td>{{ $rightTeam['green_unit'] ?? 16 }}</td>
                                <td>{{ $rightTeam['green_direct'] ?? 1 }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="tree-container">
    
    <div class="tree-legend">
    <h6>Tree View</h6>

    <div class="legend-item">
        <span class="legend-dot green"></span>
        <span>100 BV Complete</span>
    </div>

    <div class="legend-item">
        <span class="legend-dot yellow"></span>
        <span>100 BV Pending</span>
    </div>

    <div class="legend-item">
         <i class="fas fa-star" style="color:#0d6efd;"></i>
        <span>Compensation ID</span>
    </div>

    <div class="legend-item">
        <span class="legend-dot red"></span>
        <span>Inactive ID</span>
    </div>


</div>
    <h1 class="page-title">Binary Tree</h1>
    <p class="page-subtitle">Navigate your network effortlessly</p>
    <div class="controls">
        {{-- Expand/Collapse are gone: the tree now re-roots on click instead of
             unfolding in place, so Reset (back to your own tree) is what the
             user needs here. --}}
        <button class="btn btn-collapse" id="resetTree">
            <i class="fas fa-rotate-left"></i> Reset
        </button>
        <button class="btn btn-zoom-in" id="zoomIn">
            <i class="fas fa-search-plus"></i> Zoom In
        </button>
        <button class="btn btn-zoom-out" id="zoomOut">
            <i class="fas fa-search-minus"></i> Zoom Out
        </button>
    </div>
    {{-- Node styles live here rather than in the recursive partial, which
         emitted this block once per rendered node. --}}
    <style>
    .tree-status-badge{
        display:inline-block;
        width:10px;
        height:10px;
        border-radius:50%;
        margin-left:5px;
        vertical-align:middle;
        border:1px solid #fff;
        box-shadow:0 0 4px rgba(0,0,0,.3);
    }

    .compensation-badge-tree{
        margin-left:4px;
    }

    .member-image img,
    .member-image-inactive img{
        border-radius:50%;
    }

    /* --- Drill-down breadcrumb ------------------------------------------ */
    /* Plain path text, not chips: it is a trail, not a set of controls. */
    /* Kept tight: this sits between the buttons and the tree, so every pixel
       here pushes the tree further down the page. */
    /* Plain centred text flow, deliberately NOT flex.
       With display:flex + flex-wrap:wrap the default align-content is
       "stretch", so once the trail wrapped onto several lines the browser
       spread those lines across the container's whole height - which is what
       turned this into a tall empty column. Normal inline wrapping has no such
       behaviour. */
    .tree-breadcrumb{
        display:block;
        width:100%;
        text-align:center;
        margin:0 0 6px;
        font-size:12px;
        line-height:1.5;
    }
    .tree-breadcrumb .tree-crumb,
    .tree-breadcrumb .tree-crumb-sep{
        display:inline;
        vertical-align:baseline;
    }
    .tree-breadcrumb:empty{ display:none; }
    /* The tree below is transform-scaled, which lets it paint outside its
       layout box; keep the trail above that. */
    /* Belt and braces: the theme's sidebar rules target the bare element, and
       its JS adds `vertical-sidebar`. Even as a <div> this stays immune. */
    .tree-breadcrumb,
    .tree-breadcrumb.vertical-sidebar{
        position:relative !important;
        top:auto !important;
        left:auto !important;
        height:auto !important;
        min-height:0 !important;
        max-height:none !important;
        width:100% !important;
        box-shadow:none !important;
        background:none !important;
        z-index:2;
    }
    .tree-crumb{
        background:none;
        border:0;
        padding:0;
        font:inherit;
        color:#6ea8fe;
        cursor:pointer;
        white-space:nowrap;
        text-decoration:none;
    }
    .tree-crumb:hover{ text-decoration:underline; }
    .tree-crumb.is-current{
        color:inherit;
        opacity:.85;
        font-weight:600;
        cursor:default;
        text-decoration:none;
    }
    /* Spacing now comes from margins, not flex gap. */
    .tree-crumb-sep{ opacity:.45; margin:0 4px; }

    /* Compact inline pill rather than a full-width banner. */
    .tree-hidden-note{
        display:inline-flex;
        align-items:center;
        gap:5px;
        max-width:100%;
        margin:0 auto 6px;
        padding:2px 9px;
        border-radius:20px;
        background:rgba(255,193,7,.12);
        border:1px solid rgba(255,193,7,.3);
        color:#ffc107;
        font-size:11px;
        line-height:1.4;
        position:relative;
        z-index:2;
    }
    .tree-hidden-wrap{ text-align:center; }

    .tree-loading{
        text-align:center;
        padding:18px;
        opacity:.75;
        font-size:13px;
    }

    /* A node with children below it can be opened; show that it is clickable. */
    .member-view-box{ cursor:pointer; }
    .member-view-box:hover{ filter:brightness(1.12); }

    /* Member name, shown above the username. Names vary a lot in length, so
       clamp to a single ellipsised line to keep the node boxes aligned. */
    .member-name{
        max-width:110px;
        margin:2px auto 0;
        font-size:11px;
        font-weight:600;
        line-height:1.2;
        color:#b1df9a;
        text-align:center;
        white-space:nowrap;
        overflow:hidden;
        text-overflow:ellipsis;
    }

    /* The member's own chosen name, under the full name and above the ID.
       Lighter than .member-name so the three lines stay readable. */
    .member-unique{
        max-width:110px;
        margin:0 auto 1px;
        font-size:10px;
        font-weight:500;
        line-height:1.2;
        color:#9aa7b5;
        text-align:center;
        white-space:nowrap;
        overflow:hidden;
        text-overflow:ellipsis;
    }
    </style>

    {{-- Breadcrumb of the path from your own node down to whatever you have
         opened. Each hop is clickable to jump back up. --}}
    {{-- Deliberately a <div>, not a <nav>. The theme styles the bare `nav`
         element as the app sidebar (height:100vh, fixed, sidebar width) and its
         JS tags every nav with `vertical-sidebar`, which turned this trail into
         a tall empty column. role="navigation" keeps the semantics. --}}
    <div class="tree-breadcrumb" id="treeBreadcrumb" role="navigation" aria-label="Tree path"></div>

    {{-- A binary slot holds one member. If two were placed on the same side,
         one cannot be drawn - name it rather than let it vanish. --}}
    <div class="tree-hidden-wrap" id="treeHiddenNote" @if (empty($treeHidden ?? [])) hidden @endif>
        <span class="tree-hidden-note">
            <i class="fas fa-triangle-exclamation"></i>
            <span id="treeHiddenNames">{{ implode(', ', $treeHidden ?? []) }}</span>
            <span>&mdash; same slot, tree me nahi dikh raha</span>
        </span>
    </div>

    <div class="genealogy-scroll">
        <div class="genealogy-tree" id="genealogyTree">
            @if ($user)
            @include('user.tree.partials.user_node', [
                'user' => $user,
                'level' => 0,
                'treeMaxLevel' => $treeMaxLevel ?? 3,
                'treeNodes' => $treeNodes ?? [],
                'treeBv' => $treeBv ?? [],
                'treeChildren' => $treeChildren ?? [],
            ])
            @else
            <p>Please log in to view your genealogy tree.</p>
            @endif
        </div>
        <div class="tree-loading" id="treeLoading" hidden>
            <i class="fas fa-circle-notch fa-spin"></i> Loading…
        </div>
    </div>
</div>



<div class="notification" id="notification">
    <i class="fas fa-check-circle mr-2"></i> Operation successful!
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> --}}

<script>
    // Starts at natural size. This used to be 3 - outside the 0.6-1.8 range the
    // buttons allow - which meant the first updateZoom() call blew the tree up
    // to 300% and pushed it over the rest of the page.
    let zoomLevel = 1;
    const zoomStep = 0.15;
    const minZoom = 0.6;
    const maxZoom = 1.8;
    // ------------------------------------------------------------------
    // Re-root on click (same 3-level UI as default)
    //
    // Initial: logged-in sponsor on top + left/right.
    // Click any left/right id: that id becomes the new top; previous sponsor
    // disappears; left/right of that id show with the same level depth.
    // Reset / first breadcrumb: back to original sponsor tree.
    // ------------------------------------------------------------------
    @php
        $treeRootPayload = [
            'id' => $rootUser->id,
            'username' => $rootUser->username,
            'name' => $rootUser->name,
        ];
        $treePathPayload = $treePath ?? [];
    @endphp
    const TREE_FETCH_URL = @json(route('user.tree.fetchSubTree'));
    const TREE_ROOT = @json($treeRootPayload);

    let treePath = @json($treePathPayload);
    let treeBusy = false;
    let initialTreeHtml = '';

    function renderBreadcrumb() {
        const $bc = $('#treeBreadcrumb').empty();
        if (!treePath.length) return;

        treePath.forEach(function(node, i) {
            if (i > 0) {
                $bc.append($('<span class="tree-crumb-sep">&rsaquo;</span>'));
            }
            const isLast = (i === treePath.length - 1);
            $('<button type="button">')
                .addClass('tree-crumb' + (isLast ? ' is-current' : ''))
                .attr('data-id', node.id)
                .attr('title', node.name || node.username)
                .text(node.username)
                .appendTo($bc);
        });
    }

    function renderHiddenNote(names) {
        const $note = $('#treeHiddenNote');
        if (!names || !names.length) {
            $note.prop('hidden', true);
            return;
        }
        $('#treeHiddenNames').text(names.join(', '));
        $note.prop('hidden', false);
    }

    function clearForcedWidths() {
        const tree = document.getElementById('genealogyTree');
        if (!tree) return;
        tree.querySelectorAll('li').forEach(function (li) {
            li.style.flex = '0 0 auto';
            li.style.width = 'auto';
            li.style.minWidth = '';
            li.style.maxWidth = '';
        });
    }

    function afterTreeChange() {
        clearForcedWidths();
        updateZoom();
        requestAnimationFrame(function () {
            centerSponsor();
        });
    }

    function restoreSponsorTree() {
        if (initialTreeHtml) {
            $('#genealogyTree').html(initialTreeHtml);
        }
        treePath = [{
            id: TREE_ROOT.id,
            username: TREE_ROOT.username,
            name: TREE_ROOT.name
        }];
        renderBreadcrumb();
        afterTreeChange();
        showNotification('Back to your tree');
    }

    // Replace the whole tree: clicked member becomes the new top root.
    function openAsRoot(id) {
        if (treeBusy) return;

        const currentRootId = $('#genealogyTree .member-view-box').first().attr('data-user-id');
        if (String(id) === String(currentRootId)) {
            return;
        }

        // Jumping back to the logged-in sponsor restores the original markup.
        if (String(id) === String(TREE_ROOT.id)) {
            restoreSponsorTree();
            return;
        }

        treeBusy = true;
        $('#treeLoading').prop('hidden', false);

        $.getJSON(TREE_FETCH_URL, { id: id })
            .done(function(res) {
                if (!res.html) {
                    showNotification('No downline to open');
                    return;
                }
                $('#genealogyTree').html(res.html);
                if (res.root) {
                    const idx = treePath.findIndex(function(n) {
                        return String(n.id) === String(res.root.id);
                    });
                    if (idx >= 0) {
                        treePath = treePath.slice(0, idx + 1);
                    } else {
                        treePath = treePath.concat([res.root]);
                    }
                    renderBreadcrumb();
                }
                renderHiddenNote(res.hidden);
                afterTreeChange();
                showNotification('Opened ' + (res.root ? res.root.username : 'member'));
            })
            .fail(function(xhr) {
                const msg = (xhr.responseJSON && xhr.responseJSON.message)
                    ? xhr.responseJSON.message
                    : 'Could not open that member.';
                showNotification(msg);
            })
            .always(function() {
                $('#treeLoading').prop('hidden', true);
                treeBusy = false;
            });
    }

    $(document).ready(function() {
        initialTreeHtml = $('#genealogyTree').html();

        renderBreadcrumb();
        centerSponsor();
        $(window).on('resize', centerSponsor);

        // Click left/right id → that id becomes the new top (sponsor disappears).
        $(document).on('click', '#genealogyTree .member-view-box', function(e) {
            if ($(e.target).closest('.copy-username, .register-link, .copy-link').length) {
                return;
            }
            e.preventDefault();
            e.stopPropagation();

            const id = $(this).attr('data-user-id') || $(this).data('user-id');
            if (!id) return;

            openAsRoot(id);
        });

        // Breadcrumb: re-open that member as the top root (or restore sponsor).
        $(document).on('click', '.tree-crumb', function() {
            if ($(this).hasClass('is-current')) return;
            const crumbId = String($(this).attr('data-id') || $(this).data('id'));
            if (!crumbId) return;
            openAsRoot(crumbId);
        });

        $('#resetTree').on('click touchstart', function(e) {
            e.preventDefault();
            restoreSponsorTree();
        });
        $('#zoomIn').on('click touchstart', function(e) {
            e.preventDefault();
            zoomLevel = Math.min(maxZoom, zoomLevel + zoomStep);
            updateZoom();
            showNotification('Zoomed in');
        });
        $('#zoomOut').on('click touchstart', function(e) {
            e.preventDefault();
            zoomLevel = Math.max(minZoom, zoomLevel - zoomStep);
            updateZoom();
            showNotification('Zoomed out');
        });
        // Delegated so Register / copy still work after in-place expand.
        $(document).on('click touchstart', '.copy-link', function(e) {
            e.preventDefault();
            const link = $(this).data('link');
            copyToClipboard(link);
            showNotification('Link copied to clipboard!');
        });
        $(document).on('click touchstart', '.register-link', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const link = $(this).data('link');
            window.open(link, '_blank');
            showNotification('Opening registration page...');
        });
        // Press and hold to drag the tree in any direction. A small movement
        // is treated as a pan, so it does not also open the member underneath.
        const pan = document.querySelector('.genealogy-scroll');
        if (pan) {
            let dragging = false;
            let moved = false;
            let startX = 0;
            let startY = 0;
            let startLeft = 0;
            let startTop = 0;

            pan.addEventListener('pointerdown', function(e) {
                if (e.button !== 0) return;
                // Never start a pan on a member node — that stole clicks and
                // blocked opening the next left/right members.
                if (e.target.closest('.member-view-box, .copy-username, .register-link, .copy-link, button, a')) return;
                dragging = true;
                moved = false;
                startX = e.clientX;
                startY = e.clientY;
                startLeft = pan.scrollLeft;
                startTop = pan.scrollTop;
                pan.classList.add('is-grabbing');
                pan.setPointerCapture(e.pointerId);
            });

            pan.addEventListener('pointermove', function(e) {
                if (!dragging) return;
                const dx = e.clientX - startX;
                const dy = e.clientY - startY;
                if (Math.abs(dx) > 4 || Math.abs(dy) > 4) moved = true;
                pan.scrollLeft = startLeft - dx;
                pan.scrollTop = startTop - dy;
            });

            function endPan() {
                dragging = false;
                pan.classList.remove('is-grabbing');
            }

            pan.addEventListener('pointerup', endPan);
            pan.addEventListener('pointercancel', endPan);

            pan.addEventListener('click', function(e) {
                if (!moved) return;
                e.preventDefault();
                e.stopPropagation();
                moved = false;
            }, true);
        }
    });
    
    
    function centerSponsor() {
        clearForcedWidths();
        const scroller = document.querySelector('.genealogy-scroll');
        const root = document.querySelector('#genealogyTree .member-view-box');
        if (!scroller || !root) return;
        const place = function() {
            const sRect = scroller.getBoundingClientRect();
            const rRect = root.getBoundingClientRect();
            scroller.scrollLeft += (rRect.left + rRect.width / 2) - (sRect.left + sRect.width / 2);
            scroller.scrollTop = 0;
        };
        place();
        requestAnimationFrame(place);
    }
    function updateZoom() {
        const tree = document.getElementById('genealogyTree');
        if (!tree) return;
        tree.style.transform = `scale(${zoomLevel})`;
        tree.style.transformOrigin = 'top center';
        // Scale does not grow the layout box, so the extra size is added as
        // margin. Dragging can then reach the whole zoomed tree.
        const extraW = Math.max(0, tree.offsetWidth * (zoomLevel - 1));
        const extraH = Math.max(0, tree.offsetHeight * (zoomLevel - 1));
        tree.style.marginLeft = extraW ? (extraW / 2) + 'px' : '';
        tree.style.marginRight = extraW ? (extraW / 2) + 'px' : '';
        tree.style.marginBottom = extraH ? extraH + 'px' : '';
    }
    function showNotification(message) {
        const notification = $('#notification');
        notification.html(`<i class="fas fa-check-circle mr-2"></i> ${message}`);
        notification.addClass('show');
        setTimeout(() => {
            notification.removeClass('show');
        }, 1800);
    }
    function copyToClipboard(text) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(() => {
                // Success handled by showNotification
            }).catch(err => {
                console.error('Failed to copy: ', err);
                showNotification('Failed to copy link');
            });
        } else {
            // Fallback for older browsers
            const textarea = document.createElement('textarea');
            textarea.value = text;
            document.body.appendChild(textarea);
            textarea.select();
            try {
                document.execCommand('copy');
                // Success handled by showNotification
            } catch (err) {
                console.error('Fallback: Failed to copy: ', err);
                showNotification('Failed to copy link');
            }
            document.body.removeChild(textarea);
        }
    }
    
$(document).on('click', '.copy-username', function(e){
    e.preventDefault();
    e.stopPropagation(); // CRITICAL: Stops event from bubbling to parent <li>

    let username = $(this).data('username');

    if (navigator.clipboard) {
        navigator.clipboard.writeText(username).then(function () {
            showNotification('Username copied: ' + username);
        }).catch(function() {
            // Fallback for browsers that block clipboard API
            const textarea = document.createElement('textarea');
            textarea.value = username;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
            showNotification('Username copied: ' + username);
        });
    } else {
        // Fallback for older browsers
        const textarea = document.createElement('textarea');
        textarea.value = username;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        showNotification('Username copied: ' + username);
    }
});
</script>
@endsection