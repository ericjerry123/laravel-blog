<?php

namespace App\Http\Controllers;

use App\Http\Traits\LogsExceptions;
use App\Services\Interfaces\CommentServiceInterface;
use App\Services\DebugService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    use LogsExceptions;

    protected CommentServiceInterface $commentService;
    protected DebugService $debugService;

    /**
     * 構造函數
     *
     * @param CommentServiceInterface $commentService
     * @param DebugService $debugService
     */
    public function __construct(
        CommentServiceInterface $commentService,
        DebugService $debugService
    ) {
        $this->commentService = $commentService;
        $this->debugService = $debugService;
    }

    // ... existing code ...

    // 移除like方法，因為已經將點贊功能移至CommentLikeController
} 