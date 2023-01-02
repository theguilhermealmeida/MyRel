@extends('layouts.app')

@section('title', 'Contacts')

@section('content')

<div id="contact" class="container mt-5">
  <h2 class="text-center mb-4">Contact Us</h2>
  <div class="row">
    <div>
      <p>Feel free to contact us through any of the following methods:</p>
      <ul class="list-unstyled">
        <li>
          <i class="fas fa-phone fa-fw"></i>
          Phone: <a href="tel:+351123456789" class="text-secondary">+351 960 000 000</a>
        </li>
        <li>
          <i class="fas fa-envelope fa-fw"></i>
          Email: <a href="mailto:info@example.com" class="text-secondary">myrel@help.com</a>
        </li>
        <li>
          <i class="fas fa-map-marker fa-fw"></i>
          Address: R. Dr. Roberto Frias, 4200-465 Porto Portugal
        </li>
        <hr>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3002.991054361485!2d-8.598034084664112!3d41.17836231688995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd246446d48922a3%3A0x8b1e4a0bcdacc840!2sFEUP%20-%20Faculdade%20de%20Engenharia%20da%20Universidade%20do%20Porto!5e0!3m2!1spt-PT!2spt!4v1672696644167!5m2!1spt-PT!2spt" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="rounded"></iframe>
      </ul>
    </div>
  </div>
</div>



@endsection