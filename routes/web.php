<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\HospitalController as AdminHospitalController;
use App\Http\Controllers\Admin\SpecialtyController as AdminSpecialtyController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PublicController;

Route::get('/', [PublicController::class, 'home'])->name('public.home');
Route::get('/pesquisa', [PublicController::class, 'search'])->name('public.search');
Route::get('/contactos', [PublicController::class, 'contact'])->name('public.contact');
Route::post('/contactos', [PublicController::class, 'submitContact'])->name('public.contact.submit');

Route::get('/dashboard', function () {
    $appointments = auth()->user()->appointments()->with(['hospital', 'specialty'])->latest()->get();
    return view('dashboard', compact('appointments'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/medical-record', [ProfileController::class, 'updateMedicalRecord'])->name('profile.medical_record.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Appointment Receipt
    Route::get('/appointments/{id}/receipt', function ($id) {
        $appointment = \App\Models\Appointment::with(['hospital', 'specialty'])->where('user_id', auth()->id())->findOrFail($id);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.receipt', compact('appointment'));
        return $pdf->download('Comprovativo_Marcacao_' . $appointment->id . '.pdf');
    })->name('appointments.receipt');
    
    // Patient Appointments
    Route::post('/appointments', [\App\Http\Controllers\AppointmentController::class, 'store'])->name('appointments.store');
    
    // Reviews
    Route::post('/hospital/{hospital}/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
});

// Admin Routes
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('hospitals/{hospital}/toggle-active', [\App\Http\Controllers\Admin\HospitalController::class, 'toggleActive'])->name('hospitals.toggle-active');
    Route::resource('hospitals', AdminHospitalController::class);
    Route::resource('specialties', AdminSpecialtyController::class);
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->only(['index']);
    Route::get('messages', [\App\Http\Controllers\Admin\ContactMessageController::class, 'index'])->name('messages.index');
    Route::delete('messages/{message}', [\App\Http\Controllers\Admin\ContactMessageController::class, 'destroy'])->name('messages.destroy');
});

// Hospital Routes
Route::middleware(['auth', 'role:hospital'])->prefix('hospital')->name('hospital.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Hospital\DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/profile', [\App\Http\Controllers\Hospital\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\Hospital\ProfileController::class, 'update'])->name('profile.update');
    
    Route::get('/specialties', [\App\Http\Controllers\Hospital\SpecialtyController::class, 'index'])->name('specialties.index');
    Route::post('/specialties/sync', [\App\Http\Controllers\Hospital\SpecialtyController::class, 'sync'])->name('specialties.sync');
    
    Route::get('/gallery', [\App\Http\Controllers\Hospital\GalleryController::class, 'index'])->name('gallery.index');
    Route::post('/gallery', [\App\Http\Controllers\Hospital\GalleryController::class, 'store'])->name('gallery.store');
    Route::delete('/gallery/{gallery}', [\App\Http\Controllers\Hospital\GalleryController::class, 'destroy'])->name('gallery.destroy');
    
    Route::get('/appointments', [\App\Http\Controllers\Hospital\AppointmentController::class, 'index'])->name('appointments.index');
    Route::put('/appointments/{appointment}', [\App\Http\Controllers\Hospital\AppointmentController::class, 'update'])->name('appointments.update');

    // Patient Medical Record
    Route::get('/patients/{user}/medical-record', [\App\Http\Controllers\Hospital\PatientController::class, 'showMedicalRecord'])->name('patients.medical-record');

    // Hospital Reviews
    Route::get('/reviews', [\App\Http\Controllers\Hospital\ReviewController::class, 'index'])->name('reviews.index');
    Route::put('/reviews/{review}', [\App\Http\Controllers\Hospital\ReviewController::class, 'update'])->name('reviews.update');
    Route::post('/reviews/{review}/respond', [\App\Http\Controllers\Hospital\ReviewController::class, 'respond'])->name('reviews.respond');

    // Hospital Schedules
    Route::get('/schedules', [\App\Http\Controllers\Hospital\ScheduleController::class, 'index'])->name('schedules.index');
    Route::post('/schedules', [\App\Http\Controllers\Hospital\ScheduleController::class, 'store'])->name('schedules.store');
});

// Public Hospital Route (must be below /hospital/* routes to avoid matching "dashboard" as a slug)
Route::get('/hospital/{hospital:slug}', [PublicController::class, 'hospital'])->name('public.hospital');

require __DIR__.'/auth.php';
