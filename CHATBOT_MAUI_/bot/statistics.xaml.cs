using Microcharts;
using SkiaSharp;
using MySql.Data.MySqlClient;
using System.Globalization;

namespace bot
{
    public partial class statistics : ContentPage
    {
        private readonly string connectionString = "Server=localhost;Database=inquiry;User ID=root;Password=;";
        private const double SIMILARITY_THRESHOLD = 0.7;
        private Dictionary<string, int> keywordStats;
        private int othersCount;

        public statistics()
        {
            InitializeComponent();
            InitializeChart();
            LoadKeywordStatistics();
        }

        private void InitializeChart()
        {
            PieChartView.Chart = new PieChart
            {
                Entries = Array.Empty<ChartEntry>(),
                BackgroundColor = SKColors.Transparent
            };
        }

        private async void OnReloadStatisticsClicked(object sender, EventArgs e)
        {
            await LoadKeywordStatistics();
        }

        private async Task LoadKeywordStatistics()
        {
            try
            {
                using var connection = new MySqlConnection(connectionString);
                await connection.OpenAsync();

                // Get all unique keywords
                var keywords = new List<string>();
                const string getKeywordsQuery = "SELECT DISTINCT keyword FROM intents";

                using (var command = new MySqlCommand(getKeywordsQuery, connection))
                using (var reader = await command.ExecuteReaderAsync())
                {
                    while (await reader.ReadAsync())
                    {
                        keywords.Add(reader.GetString(0).ToLower());
                    }
                }

                if (keywords.Count == 0)
                {
                    await DisplayAlert("Info", "No keywords found.", "OK");
                    return;
                }

                // Get all user inputs
                var intents = new List<string>();
                const string getIntentsQuery = "SELECT input FROM logs";

                using (var command = new MySqlCommand(getIntentsQuery, connection))
                using (var reader = await command.ExecuteReaderAsync())
                {
                    while (await reader.ReadAsync())
                    {
                        intents.Add(reader.GetString(0).ToLower());
                    }
                }

                keywordStats = new Dictionary<string, int>();
                othersCount = 0;

                foreach (var keyword in keywords)
                {
                    keywordStats[keyword] = 0;
                }

                foreach (var intent in intents)
                {
                    bool matched = false;

                    foreach (var keyword in keywords)
                    {
                        if (intent.Contains(keyword))
                        {
                            keywordStats[keyword]++;
                            matched = true;
                            break;
                        }

                        foreach (var word in intent.Split(' '))
                        {
                            if (word.Length < 3) continue;

                            double similarity = CalculateSimilarity(keyword, word);
                            if (similarity >= SIMILARITY_THRESHOLD)
                            {
                                keywordStats[keyword]++;
                                matched = true;
                                break;
                            }
                        }

                        if (matched) break;
                    }

                    if (!matched)
                    {
                        othersCount++;
                    }
                }

                // Update UI with your original label design
                UpdateStatsTable();
                UpdatePieChart();
            }
            catch (Exception ex)
            {
                await DisplayAlert("Error", $"Failed to load statistics: {ex.Message}", "OK");
            }
        }

        private void UpdateStatsTable()
        {
            // Clear old data rows (preserve headers)
            while (StatsTable.Children.Count > 2)
            {
                StatsTable.Children.RemoveAt(2);
            }

            // Remove row definitions except the first header row
            while (StatsTable.RowDefinitions.Count > 1)
            {
                StatsTable.RowDefinitions.RemoveAt(1);
            }

            int row = 1;

            // Add keyword statistics - maintaining your original label design
            foreach (var kvp in keywordStats.OrderByDescending(k => k.Value))
            {
                if (kvp.Value > 0)
                {
                    StatsTable.RowDefinitions.Add(new RowDefinition { Height = GridLength.Auto });

                    // Keyword Label - matching your original design
                    var keywordLabel = new Label
                    {
                        Text = CultureInfo.CurrentCulture.TextInfo.ToTitleCase(kvp.Key),
                        FontSize = 16,
                        VerticalOptions = LayoutOptions.Center,
                        HorizontalOptions = LayoutOptions.Start,
                        HorizontalTextAlignment = TextAlignment.Start,
                        HeightRequest = 40,
                        Margin = new Thickness(5, 2)
                    };
                    Grid.SetRow(keywordLabel, row);
                    Grid.SetColumn(keywordLabel, 0);
                    StatsTable.Children.Add(keywordLabel);

                    // Count Label - matching your original design
                    var countLabel = new Label
                    {
                        Text = kvp.Value.ToString(),
                        FontSize = 16,
                        VerticalOptions = LayoutOptions.Center,
                        HorizontalOptions = LayoutOptions.End,
                        HorizontalTextAlignment = TextAlignment.End,
                        HeightRequest = 40,
                        Margin = new Thickness(5, 2)
                    };
                    Grid.SetRow(countLabel, row);
                    Grid.SetColumn(countLabel, 1);
                    StatsTable.Children.Add(countLabel);

                    row++;
                }
            }

            // Add "Others" row if there are any - with same design
            if (othersCount > 0)
            {
                StatsTable.RowDefinitions.Add(new RowDefinition { Height = GridLength.Auto });

                var keywordLabel = new Label
                {
                    Text = "Others",
                    FontSize = 16,
                    VerticalOptions = LayoutOptions.Center,
                    HorizontalOptions = LayoutOptions.Start,
                    HorizontalTextAlignment = TextAlignment.Start,
                    HeightRequest = 40,
                    Margin = new Thickness(5, 2)
                };
                Grid.SetRow(keywordLabel, row);
                Grid.SetColumn(keywordLabel, 0);
                StatsTable.Children.Add(keywordLabel);

                var countLabel = new Label
                {
                    Text = othersCount.ToString(),
                    FontSize = 16,
                    VerticalOptions = LayoutOptions.Center,
                    HorizontalOptions = LayoutOptions.End,
                    HorizontalTextAlignment = TextAlignment.End,
                    HeightRequest = 40,
                    Margin = new Thickness(5, 2)
                };
                Grid.SetRow(countLabel, row);
                Grid.SetColumn(countLabel, 1);
                StatsTable.Children.Add(countLabel);
            }
        }

        private void UpdatePieChart()
        {
            // Calculate total count
            int totalCount = keywordStats.Sum(x => x.Value) + othersCount;

            if (totalCount > 0)
            {
                var entries = new List<ChartEntry>();
                var random = new Random();

                // Add keyword entries
                foreach (var kvp in keywordStats.OrderByDescending(k => k.Value))
                {
                    if (kvp.Value > 0)
                    {
                        float percentage = (float)kvp.Value / totalCount * 100;
                        entries.Add(new ChartEntry(kvp.Value)
                        {
                            Label = CultureInfo.CurrentCulture.TextInfo.ToTitleCase(kvp.Key),
                            ValueLabel = $"{percentage:F1}%",
                            Color = SKColor.Parse(GetRandomColor())
                        });
                    }
                }

                // Add "Others" entry if exists - Fixed syntax here
                if (othersCount > 0)
                {
                    float percentage = (float)othersCount / totalCount * 100;
                    entries.Add(new ChartEntry(othersCount)
                    {
                        Label = "Others",
                        ValueLabel = $"{percentage:F1}%",
                        Color = SKColor.Parse("#808080") // Gray color for Others
                    });
                }

                // Update the chart
                PieChartView.Chart = new PieChart
                {
                    Entries = entries,
                    BackgroundColor = SKColors.Transparent,
                    LabelTextSize = 30,
                    LabelMode = LabelMode.RightOnly
                };
            }
        }

        // Helper method to generate random colors
        private string GetRandomColor()
        {
            var random = new Random();
            return $"#{random.Next(0x1000000):X6}";
        }

        private double CalculateSimilarity(string a, string b)
        {
            if (string.IsNullOrEmpty(a) || string.IsNullOrEmpty(b))
                return 0;

            if (a.Contains(b) || b.Contains(a))
                return 1.0;

            int maxLen = Math.Max(a.Length, b.Length);
            int distance = LevenshteinDistance(a, b);
            return 1.0 - (double)distance / maxLen;
        }

        private int LevenshteinDistance(string s, string t)
        {
            int n = s.Length;
            int m = t.Length;
            int[,] d = new int[n + 1, m + 1];

            if (n == 0) return m;
            if (m == 0) return n;

            for (int i = 0; i <= n; d[i, 0] = i++) ;
            for (int j = 0; j <= m; d[0, j] = j++) ;

            for (int i = 1; i <= n; i++)
            {
                for (int j = 1; j <= m; j++)
                {
                    int cost = (t[j - 1] == s[i - 1]) ? 0 : 1;
                    d[i, j] = Math.Min(
                        Math.Min(d[i - 1, j] + 1, d[i, j - 1] + 1),
                        d[i - 1, j - 1] + cost);
                }
            }
            return d[n, m];
        }
    }
}