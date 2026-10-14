<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NoticeBoard;
use Carbon\Carbon;

class NewsEventsController extends Controller
{
    /**
     * Display a listing of the news and events.
     * Separates events (popup type with images) and news (marquee type, text-only).
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch 'marquee' type notices (news) for the timeline
        $newsItems = NoticeBoard::where('type', 'marquee')
            ->where('status', 1)
            ->orderBy('added_on', 'desc')
            ->limit(5) // Display only the 5 most recent news items for the timeline
            ->get();

        // Fetch 'popup' type notices (events) with pagination for the grid
        $events = NoticeBoard::where('type', 'popup')
            ->where('status', 1)
            ->orderBy('added_on', 'desc')
            ->paginate(9); // Paginate events to display in a grid (e.g., 3x3)

        // Pass both collections to the view
        return view('user.news_events.index', compact(
            'newsItems',
            'events'
        ));
    }

    /**
     * Display the specified news or event.
     *
     * @param int $id The ID of the NoticeBoard item.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        $item = NoticeBoard::find($id);

        if (!$item) {
            // Handle case where item is not found, e.g., redirect or show 404
            // You might want to create a dedicated 404 page or flash a message.
            return redirect()->route('user.news_events.index')->with('error', 'The requested content was not found.');
        }

        return view('user.news_events.show', compact('item'));
    }
}
