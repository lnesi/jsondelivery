var gulp          = require('gulp');
var webserver     = require('gulp-webserver');
var useref        = require('gulp-useref');
var minifyCss     = require('gulp-clean-css');
var gulpif        = require('gulp-if');
var uglify        = require('gulp-uglify');
var htmlmin       = require('gulp-htmlmin');
var sass          = require('gulp-sass');
var clean         = require('gulp-clean');
var gutil         = require('gulp-util');
var zip           = require('gulp-zip');
var clear         = require('clear');
var fs            = require('fs');
var runSequence   = require('run-sequence');
var open          = require('gulp-open');
var rename        = require("gulp-rename");
var cloudfront    = require('gulp-cloudfront-invalidate-aws-publish');
var awspublish    = require('gulp-awspublish');

var delivery = JSON.parse(fs.readFileSync('./delivery.json'));



var headers = {
  'Cache-Control': 'max-age=60, no-transform, public'
};

var cfSettings = {
  distribution: '...', // Cloudfront distribution ID 
  accessKeyId: process.env.AWS_ACCESS_KEY_ID,             // Optional AWS Access Key ID 
  secretAccessKey: process.env.AWS_SECRET_ACCESS_KEY,         // Optional AWS Secret Access Key 
  wait: true,                     // Whether to wait until invalidation is completed (default: false) 
  indexRootPath: true             // Invalidate index.html root paths (`foo/index.html` and `foo/`) (default: false) 
}

var publisher = awspublish.create({
  region: 'eu-west-1',
  params: {
    Bucket: 'advresources.two-uk.net'
  },
  accessKeyId: process.env.AWS_ACCESS_KEY_ID,
  secretAccessKey: process.env.AWS_SECRET_ACCESS_KEY
});

gulp.task('deploy', function () {
 if(process.env.AWS_ACCESS_KEY_ID){
    return gulp.src('./dist/**/*')
      .pipe(rename(function (path) {
            path.dirname = '/'+'previews/'+delivery.partner.id+'/'+delivery.id+'/'+path.dirname;
      }))
      .pipe(publisher.publish(headers))
      .pipe(cloudfront(cfSettings))
      .pipe(publisher.cache())
      .pipe(awspublish.reporter());
    }else{
      console.log(gutil.colors.red('Error:'),"AWS credentiasl not set in bash envitoment. Please export in ~/.bash_profile");
      return true;
    }
});



gulp.task('minify_html', function() {
  return gulp.src('src/*.html')
    .pipe(htmlmin({collapseWhitespace: true}))
    .pipe(gulp.dest('dist/'));
});

gulp.task('serve', function() {
  gulp.src('src')
    .pipe(webserver({
      livereload: true,
      directoryListing: false,
      open: true,
      fallback:"index.html"

    }));
});

gulp.task('preview', function(){
  gulp.src(__filename)
  .pipe(open({uri:delivery.app_preview_url}));
});



gulp.task('mix', function () {
    return gulp.src('src/*.html')
        .pipe(useref())
        .pipe(gulpif('*.html',htmlmin({collapseWhitespace: true})))
        .pipe(gulpif('*.js', uglify()))
        .pipe(gulpif('*.css', minifyCss()))
        .pipe(gulp.dest('dist/'));
});

gulp.task('sass', function () {
  return gulp.src('src/scss/**/*.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('src/css'));
});

gulp.task('compress', () =>
    gulp.src(['dist/*','dist/**/*'])
        .pipe(zip(delivery.name.replaceAll(' ','_')+'.zip'))
        .pipe(gulp.dest('.'))
);

gulp.task('sass:watch', function () {
  gulp.watch('src/scss/**/*.scss', ['sass']);
});

gulp.task("images",function(){
  return gulp.src(["src/img/*","src/img/**/*"])
    .pipe(gulp.dest("dist/img"));
});

gulp.task('clean_dist', function () {
  return gulp.src('dist', {read: false})
    .pipe(clean());
});

gulp.task('clean_zip', function () {
  return gulp.src('./*.zip', {read: false})
    .pipe(clean());
});

gulp.task("backupimage",function(){
  return gulp.src(["src/backup.gif"])
    .pipe(gulp.dest("dist/"));
});

gulp.task("zip",function(){
   runSequence("clean_zip","compress");
});


gulp.task("build",function(){
  fs.stat('src/backup.gif', function(err, stat) {
      if(err == null) {
          runSequence("clean_dist","mix","images","backupimage","zip");
      } else {
           gutil.log(gutil.colors.red('ERROR:',"Backup gif missing. Please add the it to src folder before continue."));
      }
  });
});

gulp.task('default',function(){
    clear();
    
    console.log("         _                 _ ");
    console.log("   ____ | |               (_)");
    console.log("  / __ \| |_ __   ___  ___ _ ");
    console.log(" / / _` | | '_ \ / _ \/ __| |");
    console.log("| | (_| | | | | |  __/\__ \ |");
    console.log(" \ \__,_|_|_| |_|\___||___/_|");
    console.log("  \____/                     ");

    console.log("");
    console.log('Developed by @lnesi');
    console.log(gutil.colors.green('lnesi.github.io'));
    console.log("");
    console.log(gutil.colors.magenta('AVAILABLE TASKS:'));
    console.log(gutil.colors.green('serve'),"Creates local webserver and open in browser for development.");
    console.log(gutil.colors.green('build'),"Generate distribution files and create zip file for upload to Sizmek");
    console.log(gutil.colors.green('deploy'),"Upload to S3 and open Test URL");
    console.log(gutil.colors.green('sass:watch'),"Use Sass and watch changes to generate css");
    console.log(gutil.colors.green('preview'),"Open Test URL");
    console.log(gutil.colors.green('clean'),"Cleans Project");
    console.log(gutil.colors.magenta('---------------------------------------------------------------------------------------------'));
    console.log(gutil.colors.magenta('BANNER INFO:'));
    console.log(gutil.colors.green('ID:'),delivery.id);
    console.log(gutil.colors.green('Name:'),delivery.name);
    console.log(gutil.colors.green('Type:'),delivery.type.name);
    console.log(gutil.colors.green('Size:'),delivery.size.name);
    console.log(gutil.colors.green('Partner:'),delivery.partner.name,"("+delivery.partner.abbr+")");
    console.log(gutil.colors.green('Campaign:'),delivery.campaign.name,"("+delivery.campaign.abbr+")");
    console.log(gutil.colors.green('Country:'),delivery.country.name,"("+delivery.country.code+")");
    console.log(gutil.colors.green('Language:'),delivery.language.name,"("+delivery.language.code+")");
    console.log(gutil.colors.green('Region:'),delivery.region.name,"("+delivery.region.abbr+")");
    console.log(gutil.colors.green('Audience:'),delivery.audience.name,"("+delivery.audience.abbr+")");
    console.log(gutil.colors.magenta('---------------------------------------------------------------------------------------------'));
});

String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};