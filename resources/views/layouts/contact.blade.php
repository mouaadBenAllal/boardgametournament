<section class="contact-2" id="contact">
    <div class="container">
        <div class="row contact-details">
            <div class="col-sm-8 m-auto text-center">
                <h2>Contact</h2>
                <p class="lead constrain-width mt-4">Heeft u problemen en/of vragen over het systeem, staat uw favoriete bordspel
                    er niet bij en/of wilt u een toernooi toevoegen, vul gauw het formulier in en wordt u zo snel mogelijk
                    geholpen door onze medewerkers van BGT.
                </p>
                <div class="divider"></div>
                <form class="contact-form mt-4" action="/" method="POST">
                    {{csrf_field()}}
                    @if(session()->has('message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            {{ session()->get('message') }}
                        </div>
                    @endif
                    {{--@if ($errors->any())--}}
                        {{--<div class="alert alert-danger">--}}
                            {{--<ul>--}}
                                {{--@foreach ($errors->all() as $error)--}}
                                    {{--<li>{{ $error }}</li>--}}
                                {{--@endforeach--}}
                            {{--</ul>--}}
                        {{--</div>--}}
                    {{--@endif--}}


                    <div class="row">

                        <div class="col-md-6">
                            <div class="col-sm-1"><label>Naam</label></div>
                            <input type="text" class="form-control-custom mb-4" name="name">
                        </div>

                        <div class="col-md-6">
                            <div class="col-sm-1"><label>Email</label></div>
                            <input type="email" class="form-control-custom mb-4" name="email">
                        </div>
                        <br/>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-sm-1"><label>Omschrijving</label></div>
                            <textarea class="form-control-custom mb-4" rows="3" name="body"></textarea>
                            <br/>
                            <button type="submit" class="btn btn-primary btn-lg mb-4">Verstuur bericht</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</section>
