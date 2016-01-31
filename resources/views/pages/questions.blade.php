@extends('master')

@section('title', 'FAQ')

@section('content')


<p class="question">
What exactly counts as "unique visitor"?
</p>
<p class="answer">
One unique visit is counted when the visitor sees the new test for the first time. All the subsequent times when that same test is presented to the same visitor are not added to this number.
<br>
Example: You are running one test on the element that is present in every web page of your website. Jane visits your website for the first time and explores 10 web pages. Jack visits your website and quits after checking out 2 web pages, but comes back to on the next day. All this action will count as 2 unique visitors in your test.
</p>

<p class="question">
Do I need a lot of technical expertise to use A/B Testing Lab?
</p>
<p class="answer">
Not really. Installing our snippet into your website is pretty straightforward if you have at least some knowledge about HTML. Creating tests with our visual editor is a breeze. If you know some CSS and HTML, that will allow you to create more complex tests, but that's not nessecary to conduct effective testing.
</p>

<p class="question">
Are there any limits for tests?
</p>
<p class="answer">
You can run as many tests as you want.
</p>

<p class="question">
Will A/B Testing Lab work with any browser?
</p>
<p class="answer">
Visitors with all modern mobile and desktop browsers (IE6+) will be able to see your tests. Tests editor will work fine with most browsers, but it's recommended to use the latest version of your preffered browser.
</p>

<p class="question">
How does A/B Testing Lab work?
</p>
<p class="answer">
<ul>
    <li>Load your website in visual test editor.</li>
    <li>Choose the element you want to optimize.</li>
    <li>Describe a variation. You may specify different styling too.</li>
    <li>Choose a conversion event. How will you measure success?</li>
    <li>Choose the reach of your test. How many users should participate?</li>
    <li>Let the test run for a while. We will notify you when it ends.</li>
    <li>Review the results and make changes accordingly.</li>
</ul>
Once you create, edit or delete tests, they are immediately available to your visitors.
The little code snippet you copy to your website loads a script from our servers, which scans every page on your website as it is loaded, replaces specified content with variations and tracks conversions. This happens so seamlessly and quickly that your visitors do not notice anything.
</p>

<p class="question">
Does A/B Testing Lab affect the loading times?
</p>
<p class="answer">
Barely if all. The script is compressed and takes about 35Kb, depending on the number of tests you are running. Compare that to <a href="https://gigaom.com/2014/12/29/the-overweight-web-average-web-page-size-is-up-15-in-2014/">the average web page which is 1,935Kb and uses 95 HTTP requests</a>. It is completely static and it will be cached in the browser, so a file is downloaded only on the first visit or when you change the tests.<br>
To avoid "flashing" effect (when the original content is visible for a split second until it is changed), which might occur on slower computers or bigger websites, script hides web page content for 500ms. This is rarely noticeable to end user, but gives enough time for the script to put variations in their place. If you have problems with this approach or just plain do not like it, you can remove the content of the &lt;script&gt; tag while keeping the src attribute untouched.
</p>

<p class="question">
Will A/B tests affect my SEO?
<p>
<p class="answer">
No, <a href="https://support.google.com/analytics/answer/2576845?hl=en&ref_topic=1745207">according to Google</a>. Just make sure that your variations are not completely different versions of the page.
</p>

<p class="question">
Why don't you allow to specify a test goal instead of reach? I want to stop testing once noticeable improvement is reached.
</p>
<p class="answer">
    This might sound like a good idea, but in reality it's not statictically meaningful. If you run a test up until you get a result you want, the risk of getting a false-positive greatly increases. Instead you should decide on test limits in advance and then stick to it. If you do not get a concrece result, then run a new, improved test, but do not just run the same test longer.
    Read more on this topic <a href="http://blog.sumall.com/journal/optimizely-got-me-fired.html">here</a>.
</p>
<p>
Did not find the answer you were looking for? <a href="{{ URL::route('contacts') }}">Contact us now</a>.
</p>

@endsection