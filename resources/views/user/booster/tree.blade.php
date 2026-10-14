@extends('user.layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="container-xxl py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="text-primary">Booster Package Tree</h4>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-primary" id="zoomInBtn">
                <i class="fas fa-search-plus"></i>
            </button>
            <button class="btn btn-sm btn-outline-primary" id="zoomOutBtn">
                <i class="fas fa-search-minus"></i>
            </button>
            <button class="btn btn-sm btn-outline-primary" id="resetZoomBtn">
                <i class="fas fa-expand"></i>
            </button>
        </div>
    </div>

    @if(count($trees) > 0)
        @foreach($trees as $tree)
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-body p-2">
                    <div class="matrix-tree-wrapper" id="treeWrapper-{{ $loop->index }}">
                        <div class="matrix-tree" id="matrixTree-{{ $loop->index }}">
                            {!! renderTreeNode($tree) !!}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="alert alert-info text-center">
            No matrix tree data available yet.
        </div>
    @endif
</div>

@php
function renderTreeNode($node) {
    if (!$node) return '<div class="node empty">Empty</div>';

    $statusBadge = $node['status'] == 1 
        ? '<span class="badge bg-success">Active</span>' 
        : '<span class="badge bg-warning text-dark">Pending</span>';
    
    $html = '<div class="tree-node">';
    $html .= '<div class="node">';
    $html .= '<div class="fw-bold">#' . $node['id'] . '</div>';
    $html .= '<div class="small">' . $node['username'] . '</div>';
    $html .= '<div class="mt-1">' . $statusBadge . '</div>';
    $html .= '</div>';

    if (!empty($node['left']) || !empty($node['right'])) {
        $html .= '<div class="branches">';
        $html .= '<div>' . renderTreeNode($node['left'] ?? null) . '</div>';
        $html .= '<div>' . renderTreeNode($node['right'] ?? null) . '</div>';
        $html .= '</div>';
    }

    $html .= '</div>';
    return $html;
}
@endphp

<style>
/* Base styles */
.matrix-tree-wrapper {
  width: 100%;
  overflow: auto;
  -webkit-overflow-scrolling: touch;
  padding: 10px 0;
  box-sizing: border-box;
  touch-action: pan-x pan-y;
}

.matrix-tree {

  justify-content: center;
  min-width: fit-content;
  padding: 20px 0;
  transform-origin: top center;
  transition: transform 0.2s ease;
}

.tree-node {
  display: flex;
  flex-direction: column;
  align-items: center;
  position: relative;
  margin: 0 15px;
}

.node {
  background-color: #fff;
  border: 2px solid #007bff;
  color: #007bff;
  padding: 10px;
  border-radius: 8px;
  font-size: 13px;
  min-width: 120px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  text-align: center;
  z-index: 2;
  position: relative;
  white-space: nowrap;
}

.node.empty {
  background: #f8f9fa;
  color: #999;
  border-color: #ddd;
  font-style: italic;
}

/* Connector lines */

.branches {
  display: flex;
  justify-content: center;
  position: relative;
  margin-top: 40px;
  width: 100%;
}

.branches::before {
  content: '';
  position: absolute;
  top: -20px;
  left: 50%;
  transform: translateX(-50%);
  width: calc(100% - 60px);
  height: 2px;
  background: #007bff;
  z-index: 1;
}

.branches > div {
  position: relative;
  padding: 0 15px;
}

.branches > div::before {
  content: '';
  position: absolute;
  top: -20px;
  left: 50%;
  transform: translateX(-50%);
  width: 2px;
  height: 20px;
  background: #007bff;
  z-index: 1;
}

/* Mobile optimizations */
@media (max-width: 768px) {
  .matrix-tree {
    transform: scale(0.85);
    padding: 15px 0;
  }
  
  .node {
    min-width: 100px;
    padding: 8px;
    font-size: 12px;
  }
  
  .tree-node {
    margin: 0 10px;
  }
  
  .branches {
    margin-top: 30px;
  }
  
  .branches > div {
    padding: 0 10px;
  }
}

@media (max-width: 480px) {
  .matrix-tree {
    transform: scale(0.75);
    padding: 10px 0;
  }
  
  .node {
    min-width: 85px;
    padding: 6px;
    font-size: 11px;
  }
  
  .branches {
    margin-top: 25px;
  }
  
  .tree-node::after,
  .branches > div::before {
    height: 15px;
  }
  
  .branches::before {
    top: -15px;
  }
}

/* Node badges */
.node .badge {
  font-size: 0.65em;
  padding: 0.25em 0.4em;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize zoom levels for each tree
    const trees = document.querySelectorAll('.matrix-tree');
    const zoomLevels = Array(trees.length).fill(1);
    
    // Zoom functionality
    document.querySelectorAll('#zoomInBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            trees.forEach((tree, index) => {
                zoomLevels[index] = Math.min(zoomLevels[index] + 0.1, 1.5);
                tree.style.transform = `scale(${zoomLevels[index]})`;
            });
        });
    });
    
    document.querySelectorAll('#zoomOutBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            trees.forEach((tree, index) => {
                zoomLevels[index] = Math.max(zoomLevels[index] - 0.1, 0.5);
                tree.style.transform = `scale(${zoomLevels[index]})`;
            });
        });
    });
    
    document.querySelectorAll('#resetZoomBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            trees.forEach((tree, index) => {
                zoomLevels[index] = 1;
                tree.style.transform = `scale(${zoomLevels[index]})`;
            });
        });
    });
    
    // Touch pinch zoom (simplified)
    trees.forEach(tree => {
        let initialDistance = null;
        
        tree.addEventListener('touchstart', function(e) {
            if (e.touches.length === 2) {
                initialDistance = Math.hypot(
                    e.touches[0].clientX - e.touches[1].clientX,
                    e.touches[0].clientY - e.touches[1].clientY
                );
            }
        });
        
        tree.addEventListener('touchmove', function(e) {
            if (e.touches.length === 2 && initialDistance !== null) {
                const currentDistance = Math.hypot(
                    e.touches[0].clientX - e.touches[1].clientX,
                    e.touches[0].clientY - e.touches[1].clientY
                );
                
                const scale = currentDistance / initialDistance;
                const index = Array.from(trees).indexOf(tree);
                zoomLevels[index] = Math.min(Math.max(zoomLevels[index] * scale, 0.5), 1.5);
                tree.style.transform = `scale(${zoomLevels[index]})`;
            }
        });
    });
});
</script>
@endsection