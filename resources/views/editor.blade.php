<html>
	<head>
		<title>Frontend Editor</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="{{ asset('trumbowyg/dist/ui/trumbowyg.min.css') }}">
	</head>
	<body>
		<div class="container mt-5">
			<div class="row">
				<div class="mx-auto">
					<form method="POST" action="{{route('upload_post')}}">
						@csrf
						<div class="form-group">
							<label for="post_title">Post Title</label>
							<input type="text" class="form-control" name="post_title" id="post_title" aria-describedby="post_title">
						</div>
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label for="post_categories">{{ __('Post Categories') }}</label>
									<select
										multiple
										name="post_categories[]"
										class="form-control"
										id="post_categories">
										@foreach ( $categories as $category )
											<option value="{{$category['id']}}">{{$category['name']}}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="post_content">Post Content</label>
							<textarea type="text" name="post_content" class="form-control" id="post_content" rows="5" cols="500"></textarea>
							<small id="post_content" class="form-text text-muted">Editor Powered by <a href="https://alex-d.github.io/Trumbowyg/">Trumbowyg</a></small>
						</div>
						<button id="submit_post" type="gr" class="btn btn-primary">Publish Post</button>
					</form>
				</div>
			</div>
		</div>

		<!-- Import Trumbowyg -->
		<script src="trumbowyg/dist/trumbowyg.min.js"></script>
		<!-- Import all plugins you want AFTER importing jQuery and Trumbowyg -->
		<script src="trumbowyg/dist/plugins/emoji/trumbowyg.emoji.min.js"></script>

		<script>
			$('#post_content').trumbowyg();
		</script>
	</body>
</html>