#include <stdio.h>
void str_cpy(char c1[], char c2[])
{
    int i = 0;
    while ( c1[i] != '\0')
    {
        c2[i] = c1[i];
        i++;
    }
    c2[i] = '\0';
}
int main()
{
    double arr[10] = {0};
    char name[10][30] = {
        "Nayak: The Hero",
        "The Cloud-Capped Star",
        "The Music Room",
        "Pather Panchali",
        "Charulata",
        "Subarnarekha",
        "Days and Nights in the Forest",
        "The Unnamed"};
    for (int i = 0; i < 5; i++)
    {
        printf("\nOscar Jury member no-%d ", i + 1);
        for (int i = 0; i < 8; i++)
        {
            printf("Vote for %s\n", name[i]);
            double tmp;
            scanf("%lf", &tmp);
            arr[i] += tmp;
        }
    }
    for (int i = 0; i < 8; i++)
    {
        arr[i] /= 5;
    }

    for (int i = 0; i < 8; i++)
    {
        for (int j = i + 1; j < 8; j++)
        {
            if (arr[i] > arr[j])
            {
                double tmp = arr[j];
                arr[j] = arr[i];
                arr[i] = tmp;

                char tmp_c[30];
                str_cpy(name[j], tmp_c);
                str_cpy(name[i], name[j]);
                str_cpy(tmp_c, name[i]);
            }
        }
    }
    for (int i = 0; i < 8; i++)
    {
        printf("%s has raing of %lf\n", name[i], arr[i]);
    }

    return 0;
}