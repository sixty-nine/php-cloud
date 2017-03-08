# Filters

Filters are used to filter out words from the list of words and possibly change them.

A filter is a class implementing `FilterInterface`.

If the `keepWord()` method return false, the word is not added to the list.

The `filterWord()` method is called to modify the word before it is added to the list.

The class `Filters` is a container for the filters you want to apply to the list of
worlds when it is created.

It must be used to create a list of words.

    $filters =new Filters();
    $filters->addFilters(array(
        new ChangeCase(ChangeCase::LOWERCASE),
        new RemoveCharacters(),
        new RemoveTrailingCharacters(),
        new RemoveByLength(4),
    ));

    $words = new Words($filters);


# Words

The class `Words` is a container for the list of words. It contains the text of the
various added words with the count of their occurrences and their style.

You may want to manually set the style of the words contained in the list, but there
are some helpers to do it automatically (see List visitors).

You must provide a list for filters when creating the list of words.

    $filters =new Filters();
    $words = new Words($filters);

To add words to the list you can use the various `add*()` methods.
Optionally you can provide the number of occurrences of the word as second parameter.

    $words->addWord('foobar');
    $words->addWord('foobar', 5);

    // To manually change the style of a word, retrieve it from the list before modifying it.
    $word = $words->getWord('foobar')->getStyle()->setSize(20);

# List of words

A list of words is what is going to be drawned at the end. It is basically a `Word` with
some information related to the font used to draw it.

    $font = new Font(FONTS_ROOT . '/fonts/' . $data['font']);
    $style = new CloudStyle($font);
    $style->setBackgroundColor(new Color());

    $list = new TextList($style, $words);

# List visitors

The classes implementing `TextListVisitorInterface` are called list visitors.

They can be applied to the list this way:

    $visitor = new OrientationVisitor(
        OrientationVisitor::WORDS_MAINLY_HORIZONTAL
    );
    $list->applyVisitor($visitor);

# Sorting a list

A list of words can be sorted:

    $list->setSorter(new SortByOccurrence());
    $list->reset();

To apply the sort order `reset()` must be called.

The `applyVisitor()` method calls `reset()` so there's no need to call it if a visitor is
applied afterwards.

# Mixing it up

Sometimes the order the sorting and the visitor are applied is important.

For example you may not want to sort the list by angle before having changed the
orientations of the words. Similarly you may want the sort to be done before to
search the place for the words.

    // First randomly change the orientation of the list.
    $list->applyVisitor(new OrientationVisitor(
        OrientationVisitor::WORDS_MAINLY_HORIZONTAL
    ));

    // The sort the list by the orientation of the words.
    $list->setSorter(new SortByAngle(Text::DIR_HORIZONTAL));
    $list->reset();

    // And finally find a place for the words.
    $list->applyVisitor(new UsherVisitor(
        new LinearUsher($imgWidth, $imgHeight)
    ));

# Rendering an image

    $renderer = new Renderer();
    $image = $renderer->render($list, $imgWidth, $imgHeight, $showFrame);
    $image->saveToPngFile('myFile.png');

# List of filters

 * `ChangeCase` - Change the case of the words
 * `RemoveByLength` - Remove words too short or too long
 * `RemoveCharacters` - Remove characters from words
 * `RemoveTrailingCharacters` - Remove trailing punctuation at the end of the words
 * `RemoveWords` - Remove words based on a list

# List of list visitors

 * `ColorVisitor` - Modify the color of the words based on a color generator
 * `FontSizeVisitor` - Modify the font size of the words depending on their occurrences
 * `OrientationVisitor` - Randomly change the orientation of the words
 * `UsherVisitor` - Automatically find a place for the words

# List of sorters

 * `SortByAngle` - Sort the words depending on their angle
 * `SortByOccurrence` - Sort the words depending on the number of time they appeared
 * `SortByText` - Sort the words alphabetically

