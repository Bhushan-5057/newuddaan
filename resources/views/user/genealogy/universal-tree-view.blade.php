@extends('user.layouts.app')
@section('title', 'Universal Tree View')
@section('content')
    <style>
        .tree-wrapper {
            overflow-x: auto;
            padding-bottom: 1rem;
        }
        .tree {
            min-width: 500px;
            padding-left: 20px;
            position: relative;
        }
        .tree ul {
            padding-left: 20px;
            position: relative;
        }
        .tree li {
            list-style-type: none;
            position: relative;
            padding: 20px 0 0 40px;
        }
        .tree li::before {
            content: '';
            position: absolute;
            top: 0;
            left: 20px;
            border-left: 2px dashed #bbb;
            height: 100%;
            width: 0;
        }
        .tree li::after {
            content: '';
            position: absolute;
            top: 28px;
            left: 20px;
            width: 20px;
            border-top: 2px dashed #bbb;
        }
        .tree li:only-child::before,
        .tree li:only-child::after {
            display: none;
        }
        .tree-node {
            background: #fff;
            border-radius: 10px;
            padding: 10px 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
            max-width: 400px;
            flex-wrap: wrap;
        }
        .tree-node::before {
            content: '';
            width: 40px;
            height: 40px;
            min-width: 40px;
            background-color: #007bff;
            border-radius: 50%;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-weight: bold;
            font-size: 16px;
        }
        .tree-node .info {
            text-align: left;
            flex: 1;
        }
        .tree-node .info .name {
            font-weight: bold;
            font-size: 16px;
            color: #333;
        }
        .tree-node .info .meta {
            font-size: 13px;
            color: #555;
        }
        .tree-node.active::before {
            background-color: #28a745;
        }
        .tree-node.inactive::before {
            background-color: #dc3545;
        }
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .tree-wrapper {
                padding-left: 0;
            }
            .tree-node {
                flex-direction: column;
                align-items: flex-start;
                padding: 10px;
                  max-width: 150px;
            }
            .tree-node::before {
                width: 30px;
                height: 30px;
                font-size: 14px;
            }
            .tree-node .info .name {
                font-size: 14px;
            }
            .tree-node .info .meta {
                font-size: 12px;
            }
            .tree li {
                padding-left: 20px;
            }
        }
    </style>
    <div class="page-breadcrumb">
        <h4 class="py-3 breadcrumb-wrapper mb-4 " style="padding-left: 10px;">
            <span class="text-muted fw-light">My Genealogy /</span> Tree
        </h4>
        <div class="row">
            <div class="col-12">
                <h3 class="text-center mb-4">Universal Tree View</h3>
                @if($rootUser)
                    <div class="tree-wrapper">
                        <div class="tree">
                            <ul>
                                @include('user.genealogy.partials.tree-branch', ['node' => $rootUser])
                            </ul>
                        </div>
                    </div>
                @else
                    <p class="text-center text-danger">No downline users found.</p>
                @endif
            </div>
        </div>
@endsection