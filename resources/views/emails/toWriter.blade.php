<!doctype html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>E-write mail</title>
    {{-- <link href="css/email.css" rel="stylesheet"> --}}
  </head>
  <body>
    <div>
      <span class="preheader"> Hi <b>{{$details['name']}}</b></span> <br><br>
      <h3> Disclaimer</h3>
      <i class="preheader"> Read the instructions and find the attached file(if any) to work on. Plagiarised work will not be tolarated and can lead to termination of contract, maintain neat and original work while adhearing to the deadline</i>
        <h4 style="font-size: large;">Task Details</h4>
        <p>Title: <b>{{$details['subject']}}</b></p>
        <p>Number of pages: <b>{{$details['pages']}}</b></p>
        <p>Mode: <b>{{$details['mode']}}</b> </p>
        <p>Type: <b>{{$details['type']}}</b></p>
        <p>Level: <b>{{$details['level']}}</b></p>
        <p>Format: <b>{{$details['format']}}</b></p>
        <p>User Instructions: <b>{{$details['more']}}</b> </p>
        <p>Support/Admin Instructions: <b>{{$details['info']}}</b></p>
        <p>Finish by: <b>{{$details['date']}}</b></p>
      </div>
  </body>
</html>

